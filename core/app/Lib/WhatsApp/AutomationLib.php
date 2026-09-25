<?php

namespace App\Lib\WhatsApp;

use App\Constants\Status;
use App\Events\ReceiveMessage;
use App\Models\ContactFlowState;
use App\Models\CtaUrl;
use App\Models\FlowEdge;
use App\Models\FlowNode;
use App\Models\InteractiveList;
use App\Models\Message;
use App\Models\Template;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AutomationLib
{
    public function process($user, $flow, $lastState = null, $conversation, $message = null)
    {
        $whatsappAccount = $user->currentWhatsapp();

        if (!$flow || !$conversation || !$message || !$user || !$whatsappAccount) return;

        if ($lastState && $lastState->status == Status::FLOW_STATE_WAITING) {

            $lastNode = FlowNode::where('flow_id', $lastState->flow_id)
                ->where('node_id', $lastState->current_node_id)
                ->first();

            if ($lastNode && $lastNode->type == Status::NODE_TYPE_BUTTON) {
                $buttons = json_decode($lastNode->buttons_json, true);
                $clickedIndex = null;
                foreach ($buttons['buttons'] as $index => $btn) {
                    if (trim(strtolower($btn['text'])) == trim(strtolower($message))) {
                        $clickedIndex = $index;
                        break;
                    }
                }

                $buttonEdge = FlowEdge::where('flow_id', $flow->id)
                    ->where('source_node_id', $lastNode->node_id)
                    ->where('button_index', $clickedIndex)
                    ->with('targetNode')
                    ->first();

                if ($buttonEdge && $buttonEdge->targetNode) {
                    $this->sendInitialNodes($whatsappAccount, $conversation, $buttonEdge->targetNode);
                }
            }
        } else {
            $this->sendInitialNodes($whatsappAccount, $conversation, $flow->nodes->first());
        }
    }

    private function sendInitialNodes($whatsappAccount, $conversation, $node)
    {
        if (!$node) return;

        $this->sendMessageAndTrack($whatsappAccount, $conversation, $node);

        if ($node->type == Status::NODE_TYPE_BUTTON) {
            return;
        }

        $edges = FlowEdge::where('flow_id', $node->flow_id)
            ->where('source_node_id', $node->node_id)
            ->with('targetNode')
            ->get();

        foreach ($edges as $edge) {
            if ($edge->targetNode) {
                $this->sendInitialNodes($whatsappAccount, $conversation, $edge->targetNode);
            }
        }
    }

    private function sendMessageAndTrack($whatsappAccount, $conversation, $node, $buttonIndex = null)
    {
        $whatsappLib = new WhatsAppLib();
        $messageSend = [];

        if ($node->type == Status::NODE_TYPE_LIST) {
            $interactiveList = InteractiveList::where('user_id', $whatsappAccount->user_id)->find($node->interactive_list_id);
            if ($interactiveList) {
                $messageSend = $whatsappLib->sendInteractiveListMessage($conversation->contact->mobileNumber, $whatsappAccount, $interactiveList);
            }
        } elseif ($node->type == Status::NODE_TYPE_CTA_URL) {
            $ctaUrl = CtaUrl::where('user_id', $whatsappAccount->user_id)->find($node->cta_url_id);
            if ($ctaUrl) {
                $messageSend = $whatsappLib->sendCtaUrlMessage($conversation->contact->mobileNumber, $whatsappAccount, $ctaUrl);
            }
        } elseif ($node->type == Status::NODE_TYPE_BUTTON) {
            $messageSend = $whatsappLib->sendButtonMessage($conversation->contact->mobileNumber, $whatsappAccount, $node);
        } elseif ($node->type == Status::NODE_TYPE_TEMPLATE) {
            $template = Template::where('user_id', $whatsappAccount->user_id)->find($node->template_id);
            if($template) {
                $request = new Request([]);
                $messageSend = $whatsappLib->sendTemplateMessage($request, $whatsappAccount, $template, $conversation->contact,$node);
            }
        } else {
            $request = new Request([]);
            if ($node->type == Status::NODE_TYPE_TEXT) {
                $request['message'] = $node->text;
            } elseif ($node->media) {
                $filePath = getFilePath('flowBuilderMedia') . '/' . $node->media->media_path;
                $uploadedFile = new UploadedFile($filePath, basename($filePath), mime_content_type($filePath), null, true);
                $request[getNodeMediaStringType($node->media->media_type)] = $uploadedFile;
            } elseif ($node->type == Status::NODE_TYPE_LOCATION && $node->location) {
                $request['latitude'] = $node->location['latitude'];
                $request['longitude'] = $node->location['longitude'];
            }
            $messageSend = $whatsappLib->messageSend($request, $conversation->contact->mobileNumber, $whatsappAccount);
        }

        if(!$messageSend) return;

        extract($messageSend);

        $message                      = new Message();
        $message->user_id             = $whatsappAccount->user_id;
        $message->whatsapp_account_id = $whatsappAccount->id;
        $message->whatsapp_message_id = $whatsAppMessage[0]['id'];
        $message->conversation_id     = $conversation->id;
        $message->cta_url_id          = $ctaUrlId ?? 0;
        $message->interactive_list_id = $interactiveListId ?? 0;
        $message->type                = Status::MESSAGE_SENT;
        $message->message             = $node->type == Status::NODE_TYPE_TEXT ? $node->text : '';
        $message->media_id            = $mediaId;
        $message->message_type        = getIntMessageType($messageType);
        $message->location            = $location ?? null;
        $message->flow_id             = $node->flow_id;
        $message->flow_node_id        = $node->id;
        $message->media_caption       = $mediaCaption;
        $message->media_filename      = $mediaFileName;
        $message->media_url           = $mediaUrl;
        $message->media_path          = $mediaPath;
        $message->mime_type           = $mimeType;
        $message->media_type          = $mediaType;
        $message->status              = Status::SENT;
        $message->ordering            = Carbon::now();
        $message->save();

        $conversation->last_message_at   = Carbon::now();
        $conversation->save();

        $html                        = view('Template::user.inbox.single_message', compact('message'))->render();
        $lastConversationMessageHtml = view("Template::user.inbox.conversation_last_message", compact('message'))->render();

        event(new ReceiveMessage($whatsappAccount->id, [
            'html'            => $html,
            'message'         => $message,
            'newMessage'      => true,
            'newContact'      => false,
            'lastMessageHtml' => $lastConversationMessageHtml,
            'unseenMessage'   => $conversation->unseenMessages()->count() < 10 ? $conversation->unseenMessages()->count() : '9+',
            'lastMessageAt'   => showDateTime(Carbon::now()),
            'conversationId'  => $conversation->id,
            'mediaPath'       => getFilePath('conversation')
        ]));

        $state = ContactFlowState::where('conversation_id', $conversation->id)
            ->where('flow_id', $node->flow_id)
            ->first();

        if (!$state) {
            $state = new ContactFlowState();
            $state->conversation_id = $conversation->id;
            $state->flow_id = $node->flow_id;
        }

        $state->current_node_id    = $node->node_id;
        $state->status             = ($node->type == Status::NODE_TYPE_BUTTON) ? Status::FLOW_STATE_WAITING : Status::FLOW_STATE_SENT;
        $state->button_index       = $buttonIndex;
        $state->last_interacted_at = now();
        $state->save();
    }
}
