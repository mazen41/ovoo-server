<?php

namespace App\Http\Controllers;

use App\Models\WhatsappAccount;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Contact;
use App\Models\Conversation;
use App\Constants\Status;
use App\Events\ReceiveMessage;
use App\Lib\WhatsApp\AutomationLib;
use App\Lib\WhatsApp\WhatsAppLib;
use App\Lib\WhatsApp\Webhook\CoexistenceDispatcher;
use App\Models\ContactFlowState;
use App\Models\Flow;
use App\Models\User;
use libphonenumber\PhoneNumberUtil;
use App\Traits\WhatsappManager;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class WebhookController extends Controller
{
    use WhatsappManager;

    public function webhookConnect(Request $request)
    {

        $systemWebhookToken = gs('webhook_verify_token');

        if ($systemWebhookToken && $systemWebhookToken != $request->hub_verify_token) {
            return response('Invalid token', 401);
        }

        return response($request->hub_challenge)->header('Content-type', 'plain/text'); // meta need a specific type of response
    }

    public function webhookResponse(Request $request)
    {


        $entry = $request->input('entry', []);
        if (!is_array($entry))
            return;

        // Coexistence-only webhook fields never reach the legacy handling below. Meta does not mix
        // them with `messages`/`statuses` in a single delivery, so this is a whole-payload switch.
        $coexistenceDispatcher = new CoexistenceDispatcher();

        if ($coexistenceDispatcher->handles($entry)) {
            return $coexistenceDispatcher->dispatch($entry);
        }

        $receiverPhoneNumber = null;
        $senderPhoneNumber = null;
        $senderId = null;
        $messageStatus = null;
        $messageId = null;
        $messageText = null;
        $buttonReply = null;
        $listReply = null;
        $replyId = null;
        $mediaId = null;
        $mediaType = null;
        $mediaMimeType = null;
        $messageType = 'text';
        $messageCaption = null;
        $profileName = null;
        $errorMessage = null;
        $replyToWhatsappMessageId = null;

        $whatsappAccount = WhatsappAccount::where('whatsapp_business_account_id', $entry[0]['id'])->first();

        if (!$whatsappAccount)
            return;

        $user = User::active()->find($whatsappAccount->user_id);

        if (!$user)
            return;

        foreach ($entry as $entryItem) {

            foreach ($entryItem['changes'] as $change) {

                if (!is_array($change) || !isset($change['value']))
                    continue;

                if (isset($change['field']) && $change['field'] == 'message_template_status_update') {
                    sleep(10); // wait for 10 seconds until the template store
                    $this->templateUpdateNotify($change['value']['message_template_id'], $change['value']['event'], $change['value']['reason'] ?? '');
                    continue;
                };

                $metaValue = $change['value'];
                if (!is_array($metaValue))
                    continue;

                $profileName = $metaValue['contacts'][0]['profile']['name'] ?? null;
                $metaData = $metaValue['metadata'] ?? [];
                $metaMessage = $metaValue['messages'] ?? null;

                if (isset($metaData['phone_number_id'])) {
                    $receiverPhoneNumberId = $metaData['phone_number_id'];
                }

                if (isset($metaData['display_phone_number'])) {
                    $receiverPhoneNumber = $metaData['display_phone_number'];
                }

                if (isset($metaMessage[0]['from'])) {
                    $senderPhoneNumber = $metaMessage[0]['from'];
                }

                if (isset($metaMessage[0]['id'])) {
                    $senderId = $metaMessage[0]['id'];
                }

                if (isset($metaMessage[0]['context']['id'])) {
                    $replyToWhatsappMessageId = $metaMessage[0]['context']['id'];
                }

                if (isset($change['value']['statuses'][0]['id'])) {
                    $messageId = $change['value']['statuses'][0]['id'];
                }

                if (isset($change['value']['statuses'][0]['errors'][0])) {

                    $errorMessage =  isset($change['value']['statuses'][0]['errors'][0]['title']) ? $change['value']['statuses'][0]['errors'][0]['title'] : '';

                    if (isset($change['value']['statuses'][0]['errors'][0]['error_data']['details'])) {
                        $errorMessage .= ' - ' . $change['value']['statuses'][0]['errors'][0]['error_data']['details'];
                    }
                }

                if (isset($change['value']['statuses'][0]['status'])) {
                    $messageStatus = $change['value']['statuses'][0]['status'];
                }

                if (isset($metaMessage[0]['text']['body']) || isset($metaMessage[0]['button']['text'])) {
                    $messageText = $metaMessage[0]['button']['text'] ?? $metaMessage[0]['text']['body'];
                }

                if (isset($metaMessage[0]['type'])) {
                    $messageType = $metaMessage[0]['type'];
                }

                if ($messageType == 'interactive') {
                    if (isset($metaMessage[0]['interactive']['button_reply']['title'])) {
                        $buttonReply = $metaMessage[0]['interactive']['button_reply']['title'];
                        // The id is what tells the flow which branch was taken; the label
                        // cannot, because two buttons may carry the same one.
                        $replyId = $metaMessage[0]['interactive']['button_reply']['id'] ?? null;
                    }
                    if (isset($metaMessage[0]['interactive']['list_reply']['title'])) {
                        $listReply = [
                            'title' => $metaMessage[0]['interactive']['list_reply']['title'] ?? '',
                            'description' => $metaMessage[0]['interactive']['list_reply']['description'] ?? '',
                        ];
                        $replyId = $metaMessage[0]['interactive']['list_reply']['id'] ?? null;
                    }
                }

                // A quick reply on a *template* arrives as type "button", not "interactive",
                // and carries only the label.
                if ($messageType == 'button' && isset($metaMessage[0]['button']['text'])) {
                    $buttonReply = $metaMessage[0]['button']['text'];
                }

                // Handle media messages
                if (isset($metaMessage[0]['type']) && $metaMessage[0]['type'] !== 'text') {
                    $mediaType = $metaMessage[0]['type'];

                    if (isset($metaMessage[0][$mediaType]['id'])) {
                        $mediaId = $metaMessage[0][$mediaType]['id'];
                    }
                    if (isset($metaMessage[0][$mediaType]['mime_type'])) {
                        $mediaMimeType = $metaMessage[0][$mediaType]['mime_type'];
                    }
                    if (isset($metaMessage[0][$mediaType]['caption'])) {
                        $messageCaption = $metaMessage[0][$mediaType]['caption'];
                    }
                }
            }
        }
        if ($messageId && $messageStatus) {

            $wMessage = Message::where('whatsapp_message_id', $messageId)->first();

            if ($wMessage) {

                $messageStatus = messageStatus($messageStatus);
                $wMessage->status = $messageStatus;

                if ($messageStatus == Status::FAILED) {
                    $wMessage->error_message = $errorMessage;
                }

                $wMessage->save();

                $isNewMessage = false;

                if ($wMessage->status == Status::SENT || $wMessage->status == Status::FAILED) {
                    $isNewMessage = true;
                }

                $message = $wMessage;

                // The status is already saved; a failing realtime push must not answer 500.
                try {
                    $html = view('Template::user.inbox.single_message', compact('message'))->render();

                    event(new ReceiveMessage($whatsappAccount->id, [
                        'html' => $html,
                        'messageId' => $message->id,
                        'message' => $message,
                        'statusHtml' => $message->statusBadge,
                        'newMessage' => $isNewMessage,
                        'mediaPath' => s3_configured() ? url('api/inbox/media/by-path/') : getFilePath('conversation') . '/',
                        'conversationId' => $wMessage->conversation_id,
                        'unseenMessage' => $wMessage->conversation->unseenMessages()->count() < 10 ? $wMessage->conversation->unseenMessages()->count() : '9+',
                    ]));
                } catch (\Throwable $ex) {
                    Log::error('Webhook realtime status update failed for message ' . $message->id . ': ' . $ex->getMessage());
                }

                return response()->json(['status' => 'received'], 200);
            }
        }

        if (($messageText || $buttonReply || $listReply || $mediaId) && $senderPhoneNumber && $senderId) {
            // Save the incoming message first
            $receiverPhoneNumber = preg_replace('/\D/', '', $receiverPhoneNumber);
            $phoneUtil = PhoneNumberUtil::getInstance();
            $parseNumber = $phoneUtil->parse('+' . $senderPhoneNumber, '');
            $countryCode = $parseNumber->getCountryCode();
            $nationalNumber = $parseNumber->getNationalNumber();
            $newContact = false;

            $contact = Contact::where('mobile_code', $countryCode)
                ->where('mobile', $nationalNumber)
                ->where('user_id', $user->id)
                ->with('conversation')
                ->first();

            if (!$contact) {
                $newContact = true;
                $contact = new Contact();
                $contact->firstname = $profileName;
                $contact->mobile_code = $countryCode;
                $contact->mobile = $nationalNumber;
                $contact->user_id = $user->id;
                $contact->save();
            }

            if (trim($messageText ?? '') === 'STOP') {
                $contact->is_marketing_opted_out = Status::YES;
                $contact->save();
            }elseif(trim($messageText ?? '') === 'START') {
                $contact->is_marketing_opted_out = Status::NO;
                $contact->save();
            }

            $conversation = Conversation::where('contact_id', $contact->id)->where('user_id', $user->id)->where('whatsapp_account_id', $whatsappAccount->id)->first();

            if (!$conversation) {
                $newContact = true;
                $conversation = $this->createConversation($contact, $whatsappAccount);
            }

            $messageExists = Message::where('whatsapp_message_id', $senderId)->exists();

            $whatsappLib = new WhatsAppLib();
            $automationLib = new AutomationLib();

            if (!$messageExists) {
                $replyToMessage = null;

                if ($replyToWhatsappMessageId) {
                    $replyToMessage = Message::where('whatsapp_message_id', $replyToWhatsappMessageId)
                        ->where('whatsapp_account_id', $whatsappAccount->id)
                        ->where('conversation_id', $conversation->id)
                        ->first();
                }

                // Save the incoming message
                $message                      = new Message();
                $message->whatsapp_account_id = $whatsappAccount->id;
                $message->whatsapp_message_id = $senderId;
                $message->user_id             = $user->id ?? 0;
                $message->conversation_id     = $conversation->id;
                $message->message             = $messageText ?? $buttonReply ?? '';
                $message->list_reply          = $listReply;
                $message->type                = Status::MESSAGE_RECEIVED;
                $message->message_type        = getIntMessageType($messageType);
                $message->media_id            = $mediaId;
                $message->media_type          = $mediaType;
                $message->media_caption       = $messageCaption;
                $message->mime_type           = $mediaMimeType;
                $message->reply_to_id         = $replyToMessage?->id ?? 0;
                $message->ordering            = Carbon::now();
                
                $message->save();

                $conversation->last_message_at = Carbon::now();
                $conversation->save();

                // If it's a media message, fetch and store the media
                if ($mediaId) {
                    $accessToken = $whatsappAccount->access_token;
                    try {
                        $mediaUrl = $whatsappLib->getMediaUrl($mediaId, $accessToken);

                        if ($mediaUrl && in_array($mediaType, ['image', 'audio', 'video'])) {
                            $mediaPath = $whatsappLib->storedMediaToLocal($mediaUrl['url'], $mediaId, $accessToken, $user->id);
                            $message->media_url = $mediaUrl['url'] ?? null;
                            $message->media_path = $mediaPath;

                            $message->save();
                        }
                    } catch (Exception $ex) {
                    }
                }

                // The realtime push and the notification are extras. The broadcast is sent
                // synchronously, so a Pusher error (wrong keys, wrong cluster) used to answer
                // 500 here, before the welcome message or any flow ran, and Meta's retry was
                // then dropped as a duplicate by $messageExists.
                try {
                    $html = view('Template::user.inbox.single_message', compact('message'))->render();
                    $lastConversationMessageHtml = view("Template::user.inbox.conversation_last_message", compact('message'))->render();

                    event(new ReceiveMessage($whatsappAccount->id, [
                        'html' => $html,
                        'message' => $message,
                        'newMessage' => true,
                        'newContact' => $newContact,
                        'lastMessageHtml' => $lastConversationMessageHtml,
                        'unseenMessage' => $conversation->unseenMessages()->count() < 10 ? $conversation->unseenMessages()->count() : '9+',
                        'lastMessageAt' => showDateTime(Carbon::now()),
                        'conversationId' => $conversation->id,
                        'mediaPath' => s3_configured() ? url(getFilePath('conversation')) . '/' : getFilePath('conversation') . '/'
                    ]));
                } catch (\Throwable $ex) {
                    Log::error('Webhook realtime update failed for message ' . $message->id . ': ' . $ex->getMessage());
                }

                try {
                    notify($user, 'MESSAGE_RECEIVED', [
                        'message_receiver' => $user->fullname,
                        'message_sender' => $contact->firstname ?? $contact->mobile_code . $contact->mobile,
                        'message_content' => strLimit(($messageText ?? $buttonReply ?? ''), 50),
                    ], null, true, null, route('user.inbox.list'));
                } catch (\Throwable $ex) {
                    Log::error('Webhook notification failed for message ' . $message->id . ': ' . $ex->getMessage());
                }
            }

            $messagesInConversation = Message::where('conversation_id', $conversation->id)->where('type', Status::MESSAGE_RECEIVED)->count();

            if ($whatsappAccount->is_coexistence && !$whatsappAccount->coexistence_automation) {
                // On a coexistence number a human is answering from the handset, so the welcome
                // message, automation flows and the AI auto-reply would land on top of them.
            } elseif (!$messageExists) {

                $automationFlowQuery = Flow::where('user_id', $user->id)->where('whatsapp_account_id', $whatsappAccount->id)->with(['nodes', 'nodes.media'])->active();

                $lastState = ContactFlowState::where('conversation_id', $conversation->id)
                    ->latest('last_interacted_at')
                    ->first();

                // The reply id when the contact tapped something, because button ids are
                // positional and two buttons may share a label.
                $replyValue = $replyId ?? $buttonReply ?? (($listReply['title'] ?? null) ?: null);
                $keyword    = strtolower(trim((string) $messageText));
                $queryText  = $replyValue ?? $keyword;

                $automationFlow = null;

                // 1. An answer to a question the flow already asked belongs to that flow,
                //    whether it came from an interactive button, a list row or a template
                //    quick reply.
                //    Scoped like every other lookup here, so a flow the user has since
                //    disabled stops resuming instead of running on for whoever was mid way
                //    through it.
                if ($replyValue && $lastState && $lastState->status == Status::FLOW_STATE_WAITING) {
                    $automationFlow = (clone $automationFlowQuery)->find($lastState->flow_id);
                }

                // 2. Otherwise a keyword starts - or restarts - its flow. Matched trimmed
                //    and case insensitively, which is how people actually type.
                if (!$automationFlow && $keyword !== '') {
                    $automationFlow = (clone $automationFlowQuery)->keywordMatch()
                        ->whereRaw('LOWER(TRIM(keyword)) = ?', [$keyword])
                        ->first();
                }

                // 3. Finally, the first message of a conversation can open a "new message"
                //    flow. Keyword flows are tried first so they still work on a first
                //    message, which they never used to.
                if (!$automationFlow && $messagesInConversation == 1) {
                    $automationFlow = (clone $automationFlowQuery)->newMessage()->first();
                }

                if ($automationFlow) {
                    if ($lastState && $automationFlow->id != $lastState->flow_id) {
                        // Scoped by conversation only: rows written before this release
                        // never had user_id populated.
                        ContactFlowState::where('conversation_id', $conversation->id)->delete();
                        $lastState = null;
                    }

                    try {
                        $automationLib->process($user, $automationFlow, $lastState, $conversation, $queryText);
                    } catch (Exception $ex) {
                        // A failing flow must never make the webhook answer non-200: Meta
                        // would redeliver it and $messageExists would then swallow the retry.
                        Log::error('Flow ' . $automationFlow->id . ' failed for conversation ' . $conversation->id . ': ' . $ex->getMessage());
                    }
                } elseif ($messagesInConversation == 1 && @$whatsappAccount->welcomeMessage && @$whatsappAccount->welcomeMessage->status == Status::ENABLE) {
                    // Only when no flow answered, otherwise a welcome message permanently
                    // hid every "new message" flow.
                    $this->sendWelcomeMessage($whatsappAccount, $user, $contact, $conversation);
                } else {
                    $whatsappLib->sendAutoReply($user, $conversation, $messageText);
                }
            }
        }

        return response()->json(['status' => 'received'], 200);
    }

    private function createConversation($contact, $whatsappAccount)
    {
        $conversation = new Conversation();
        $conversation->contact_id = $contact->id;
        $conversation->whatsapp_account_id = $whatsappAccount->id;
        $conversation->user_id = $whatsappAccount->user_id;
        $conversation->save();

        return $conversation;
    }
}
