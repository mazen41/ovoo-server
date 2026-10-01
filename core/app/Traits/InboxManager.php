<?php

namespace App\Traits;

use Addons\TeleWpp\Source\Lib\TelegramManager;
use App\Constants\Status;
use App\Lib\AiAssistantLib\Gemini;
use App\Lib\AiAssistantLib\OpenAi;
use App\Lib\CurlRequest;
use App\Lib\WhatsApp\WhatsAppLib;
use App\Models\AiAssistant;
use App\Models\ContactNote;
use App\Models\Conversation;
use App\Models\CtaUrl;
use App\Models\InteractiveList;
use App\Models\Message;
use App\Models\Template;
use App\Models\User;
use App\Models\WhatsappAccount;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

trait InboxManager
{
    public function list()
    {
        $user            = getParentUser();
        $contactId       = request()->contact_id;
        $conversationId  = request()->conversation ?? 0;
        $whatsappAccount = getWhatsappAccount($user);

        if ($contactId && $whatsappAccount) {
            $conversation = Conversation::where('user_id', $user->id)->where('contact_id', $contactId)->where('whatsapp_account_id', $whatsappAccount->id)->first();
            if (!$conversation) {
                $conversation                      = new Conversation();
                $conversation->user_id             = $user->id;
                $conversation->contact_id          = $contactId;
                $conversation->whatsapp_account_id = $whatsappAccount->id;
                $conversation->save();
            }
            $conversationId = $conversation->id;
        }

        $templates = Template::where('user_id', $user->id)->orderBy('id', 'desc')->get();
        $pageTitle = "Manage Inbox";
        $view      = 'Template::user.inbox.whatsapp_account_empty';

        if ($whatsappAccount) {
            $view = 'Template::user.inbox.list';
        }

        $ctaUrls          = CtaUrl::where('user_id', $user->id)->get();
        $interactiveLists = InteractiveList::where('user_id', $user->id)->get();

        return responseManager("inbox", $pageTitle, "success", [
            'view'              => $view,
            'pageTitle'         => $pageTitle,
            'conversationId'    => @$conversationId,
            'conversation'      => @$conversation,
            'whatsappAccount'   => @$whatsappAccount,
            'ctaUrls'           => $ctaUrls,
            'interactiveLists'  => $interactiveLists,
            'templates'         => $templates
        ]);
    }

    public function conversationList(Request $request)
    {
        $user  = getParentUser();
        $query = Conversation::where('user_id', $user->id)
            ->whereHas('contact')
            ->searchable(['contact:firstname,lastname,mobile'])
            ->with(['contact', 'contact.lists', 'contact.tags', 'lastMessage'])
            ->withCount('unseenMessages as unseen_messages');

        if (addonIsInstalled('tele-wpp')) {
            if (request('channel') && request('channel') == Status::CHANNEL_TELEGRAM) {
                $query->where('conversation_channel', Status::CHANNEL_TELEGRAM);
                if ($request->bot_id) {
                    $query->where('telegram_bot_id', $request->bot_id);
                }
            } else {
                $query->where('whatsapp_account_id', getWhatsappAccountId($user))->where('conversation_channel', Status::CHANNEL_WHATSAPP);
            }
        } else {
            $query->where('whatsapp_account_id', getWhatsappAccountId($user))->where('conversation_channel', Status::CHANNEL_WHATSAPP);
        }

        $query->orderBy('last_message_at', 'desc');

        if ($request->status && $request->status != 0  && $request->status !=  "assigned") {
            if ($request->status == Status::UNREAD_CONVERSATION) {
                $query->whereHas('unseenMessages');
            } else {
                $query->where('status', $request->status);
            }
        }

        if (auth()->user()->parent_id) {
            if ($request->status == 'assigned' || !$request->status) {
                $query->where(function ($q) {
                    $q->where('agent_id', auth()->user()->id)->orWhere('agent_id', 0);
                });
            }
        }

        $conversations    = $query->apiQuery();
        $html             = null;
        $conversationList = null;

        if (isApiRequest()) {
            $conversationList = $conversations;
        } else {
            $activeConversationId = request()->conversation_id ?? 0;
            $html                 = view('Template::user.inbox.conversation_list', compact('conversations', 'activeConversationId'))->render();
            $conversationList     = $conversations;
        }

        $notify[] = "Chat list";
        return apiResponse(
            "chat_list",
            "success",
            $notify,
            [
                'conversations' => $conversationList,
                'html'          => $html,
                'profilePath'   => getFilePath('contactProfile'),
                'more'          => $conversations->hasMorePages()
            ]
        );
    }

    public function conversationMessages($conversationId)
    {
        $user         = getParentUser();
        $conversation = Conversation::where('user_id', $user->id)->with('contact');
        $telegramIsInstalled = addonIsInstalled('tele-wpp');


        if (request('channel') && request('channel') == Status::CHANNEL_TELEGRAM && $telegramIsInstalled) {
            $conversation->where('conversation_channel', Status::CHANNEL_TELEGRAM);
            $channel = Status::CHANNEL_TELEGRAM;
        } else {
            $conversation->where('conversation_channel', Status::CHANNEL_WHATSAPP);
            $channel = Status::CHANNEL_WHATSAPP;
        }

        $conversation = $conversation->where('id', $conversationId)->first();

        if (!$conversation) {
            $notify[] = 'The conversation is not found';
            return apiResponse("not_found", "error", $notify);
        }


        $authUser = auth()->user();

        if ($conversation->agent_id && $authUser->parent_id && $conversation->agent_id != $authUser->id) {
            $notify[] = 'You are not authorized to view this conversation';
            return apiResponse("restricted", "error", $notify);
        }

        $messageQuery = Message::where('conversation_id', $conversationId)
            ->with('replyTo')
            ->searchable(['message']);

        $messages         = $messageQuery->orderBy('ordering', 'desc')->paginate();
        $html             = null;
        $statusHtml       = null;
        $aiReplyHtml      = null;
        $assignHtml       = null;

        if (!isApiRequest()) {
            $html        = view('Template::user.inbox.messages', compact('messages', 'channel'))->render();
            $statusHtml  = view('Template::user.inbox.conversation_status_dropdown_list', compact('conversation'))->render();
            $aiReplyHtml = view('Template::user.inbox.conversation_ai_reply_dropdown_list', compact('conversation'))->render();
            $assignHtml  = view('Template::user.inbox.conversation_assign_dropdown_list', compact('conversation', 'user'))->render();
        }

        $notify[] = "Chat messages";

        return apiResponse(
            "chat_messages",
            "success",
            $notify,
            [
                'messages'            => $messages,
                'contact'             => $conversation->contact,
                'profilePath'         => getFilePath('contactProfile'),
                'mediaBasePath'       => s3_configured() ? 'api/inbox/media/by-path' : getFilePath('conversation'),
                'html'                => $html,
                'more'                => $messages->hasMorePages(),
                'whatsapp_account_id' => @$user->currentWhatsapp()?->id,
                'status_html'         => $statusHtml,
                'ai_reply_html'       => $aiReplyHtml,
                'assign_html'         => $assignHtml,
            ]
        );
    }

    public function updateMessageStatus($id)
    {
        $user          = getParentUser();
        $conversation  = Conversation::where('user_id', $user->id)->find($id);
        if (!$conversation) {
            return;
        }
        $whatsapp      = WhatsappAccount::where('user_id', $user->id)->where('id', $conversation->whatsapp_account_id)->first();

        if (!$whatsapp) {
            return;
        }

        $messages = Message::where('conversation_id', $conversation->id)
            ->where('type', Status::MESSAGE_RECEIVED)
            ->whereIn('status', [Status::SENT, Status::DELIVERED])
            ->get();

        foreach ($messages ?? [] as $message) {

            $url = "https://graph.facebook.com/v22.0/{$whatsapp->phone_number_id}/messages";

            $requestData = [
                'messaging_product' => 'whatsapp',
                'status'            => 'read',
                'message_id'        => $message->whatsapp_message_id,
                'typing_indicator'  => [
                    'type' => 'text'
                ]
            ];

            $header = [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $whatsapp->access_token
            ];

            $data = CurlRequest::curlPostContent($url, $requestData, $header);

            $data = json_decode($data, true);

            if (isset($data['error'])) {
                continue;
            }

            if (isset($data['success'])) {
                $message->status = Status::READ;
                $message->save();
            }
        }

        return apiResponse("status_updated", "success", ["Status updated successfully"], [
            'unseenMessageCount' => $conversation->unseenMessages()->count(),
        ]);
    }

    public function changeConversationStatus(Request $request, $conversationId)
    {
        $request->validate([
            'status' => ['nullable', "integer", Rule::in([Status::DONE_CONVERSATION, Status::PENDING_CONVERSATION, Status::IMPORTANT_CONVERSATION, 0,Status::UNREAD_CONVERSATION])]
        ]);

        $user         = getParentUser();
        $conversation = Conversation::where('user_id', $user->id)->find($conversationId);

        if (!$conversation) {
            return apiResponse("not_found", "error", ["Conversation not found"]);
        };

        $conversation->status = $request->status ?? 0;
        $conversation->save();

        if (isApiRequest()) {
            $statusHtml = null;
        } else {
            $statusHtml = view('Template::user.inbox.conversation_status_dropdown_list', compact('conversation'))->render();
        }

        return apiResponse("status_updated", "success", ["Status updated successfully"], [
            'status_html' => $statusHtml
        ]);
    }

    public function changeConversationAIReplyStatus(Request $request, $conversationId)
    {
        $user         = getParentUser();
        $conversation = Conversation::where('user_id', $user->id)->find($conversationId);

        if (!$conversation) {
            return apiResponse("not_found", "error", ["Conversation not found"]);
        };

        $conversation->ai_reply = $request->status;
        $conversation->save();

        $aiReplyHtml = view('Template::user.inbox.conversation_ai_reply_dropdown_list', compact('conversation'))->render();

        return apiResponse("status_updated", "success", ["AI Reply Status updated successfully"], [
            'ai_reply_html' => $aiReplyHtml
        ]);
    }
    public function assignConversation($conversationId, $agentId)
    {
        $user         = getParentUser();
        $conversation = Conversation::where('user_id', $user->id)->find($conversationId);

        if (!$conversation) {
            return responseManager("not_found", "The conversation is not found");
        };

        $agent = User::active()->where('id', $agentId)->where('parent_id', $user->id)->where('is_deleted', Status::NO)->first();

        if (!$agent) {
            return responseManager("not_found", "The agent is not found");
        };

        if ($conversation->agent_id == $agent->id) {
            $message = "Agent remove successfully";
            $agentId = 0;
        } else {
            $message = "Agent assign successfully";
            $agentId = $agent->id;
        }

        $conversation->agent_id = $agentId;
        $conversation->save();

        return responseManager("success", $message, 'success');
    }


    public function conversationOptions($conversationId)
    {
        $user         = getParentUser();
        $conversation = Conversation::where('user_id', $user->id)->find($conversationId);

        if (!$conversation) {
            return apiResponse("not_found", "error", ["Conversation not found"]);
        };

        $html = view('Template::user.inbox.conversation_options', compact('conversation'))->render();
        return apiResponse("conversation_options", "success", ["Conversation Options Fetch successfully"], [
            'html' => $html
        ]);
    }

    public function contactDetails($conversationId)
    {
        $user         = getParentUser();
        $conversation = Conversation::where('user_id', $user->id)->with(['contact', 'notes', 'contact.tags', 'contact.lists'])->find($conversationId);

        if (!$conversation) {
            $notify[] = 'Conversation not found';
            return apiResponse("conversation_details", "error", $notify);
        };

        $notify[] = 'Conversation details';
        $html     = null;
        $contact  = $conversation->contact;

        if (!isApiRequest()) {
            $html = view('Template::user.inbox.contact_details', compact('conversation'))->render();
        }

        $clearChatRoute = route('user.inbox.conversation.clear', $conversation->id);

        return apiResponse("conversation_details", "success", $notify, [
            'conversation'   => $conversation,
            'profilePath'    => getFilePath('contactProfile'),
            'html'           => $html,
            'isBlocked'      => $contact->is_blocked,
            'clearChatRoute' => $clearChatRoute
        ]);
    }

    public function clearConversationMessage($id)
    {
        $user         = getParentUser();
        $conversation = Conversation::where('user_id', $user->id)->find($id);

        if (!$conversation) {
            return responseManager('not_found', 'Conversation not found');
        }

        $messages = $conversation->messages;

        if (count($messages) > 0) {
            foreach ($messages as $message) {
                if ($message->media_path) {
                    if (s3_configured()) {
                        s3_disk()->delete('conversation/' . $message->media_path);
                    } elseif (file_exists(getFilePath('conversation') . '/' . $message->media_path)) {
                        unlink(getFilePath('conversation') . '/' . $message->media_path);
                    }
                }
                $message->delete();
            }
        } else {
            return responseManager('not_found', 'The conversation is empty');
        }

        return responseManager('conversation_cleared', 'Conversation cleared successfully', 'success');
    }

    public function storeNote(Request $request)
    {
        $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'note'            => 'required|string|max:255',
        ]);

        $contactNote                  = new ContactNote();
        $contactNote->conversation_id = $request->conversation_id;
        $contactNote->note            = $request->note;
        $contactNote->user_id         = getParentUser()->id;
        $contactNote->save();

        $message = "Note saved successfully";
        return responseManager("note_saved", $message, "success", ['note' => $contactNote]);
    }

    public function deleteNote($id)
    {
        $user = getParentUser();
        $note = ContactNote::where('user_id', $user->id)->find($id);

        if (!$note) {
            return apiResponse("note_not_found", "error", ["The note is not found"]);
        }

        $note->delete();

        $notify[] = "Note deleted successfully";
        return apiResponse("note_deleted", "success", $notify);
    }

    public function sendMessage(Request $request)
    {
        if (request('channel') && request('channel') == Status::CHANNEL_TELEGRAM && addonIsInstalled('tele-wpp')) {
            return (new TelegramManager)->sendMessage($request);
        }

        $request->validate([
            'message'             => 'required_without_all:image,document,video,audio,animation_url,cta_url_id,latitude,longitude,interactive_list_id,product,created_order_data',
            'conversation_id'     => 'required',
            'image'               => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'document'            => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:102400'],
            'video'               => ['nullable', 'file', 'mimes:mp4', 'max:16384'],
            'audio'               => 'nullable|file|max:16384',
            'animation_url'       => ['nullable', 'url', 'max:2048'],
            'picker_media_type'   => ['nullable', 'in:sticker,gif'],
            'cta_url_id'          => 'nullable|int',
            'interactive_list_id' => 'nullable|int',
            'latitude'            => 'required_if:longitude,!=,0',
            'longitude'           => 'required_if:latitude,!=,0',
        ], [
            'conversation_id.required'     => 'Please select a conversation to send message.',
            'message.required_without_all' => 'The message should not be empty.',
            'longitude.required_if'         => 'The longitude field is required when latitude is not empty.',
        ]);

        $ctaUrl             = null;
        $interactiveList    = null;
        $wooCommerceProduct = null;
        $createdOrderData   = null;
        $ctaUrlData         = null;
        $replyToMessage     = null;
        $user               = getParentUser();
        $conversation       = Conversation::where('user_id', $user->id)->find($request->conversation_id);

        if (!$conversation) {
            return apiResponse("not_found", "error", ["The conversation is not found"]);
        }

        $contact = $conversation->contact;

        if ($contact->is_blocked) {
            return apiResponse("not_found", "error", ["You cannot send or receive messages with this contact because they have been blocked."]);
        }

        if ($request->cta_url_id) {
            $ctaUrl = CtaUrl::where('user_id', $user->id)->find($request->cta_url_id);
            if (!$ctaUrl) {
                return apiResponse("not_found", "error", ["The cta url is not found"]);
            }
        }

        if ($request->interactive_list_id) {
            $interactiveList = InteractiveList::where('user_id', $user->id)->find($request->interactive_list_id);
            if (!$interactiveList) {
                return apiResponse("not_found", "error", ["The interactive list is not found"]);
            }
        }

        if ($request->product) {
            $wooCommerceProduct = json_decode($request->product, true);
        }

        if ($request->created_order_data) {
            $createdOrderData = json_decode($request->created_order_data, true);
        }

        if($request->wa_message_id) {
            $replyToMessage = Message::query()->where('conversation_id',$conversation->id)->find($request->wa_message_id);
        }

        try {
            $whatsappAccount  = getWhatsappAccount($user);

            if (!$whatsappAccount) {
                return apiResponse("not_found", "error", ["The whatsapp account is not found"]);
            }

            if ($wooCommerceProduct) {
                $ctaUrlData = makeCtaUrlFromProduct($wooCommerceProduct);
            }

            if ($createdOrderData) {
                $ctaUrlData = makeCtaUrlFromCreatedOrder($createdOrderData);
            }

            if ($ctaUrl || $interactiveList || $ctaUrlData) {

                if (!featureAccessLimitCheck($user->interactive_message)) {
                    return apiResponse("not_found", "error", ["Your current plan does not support interactive messages. Please upgrade your plan."]);
                }

                if ($ctaUrl) {
                    $messageSend = (new WhatsAppLib())->sendCtaUrlMessage($conversation->contact->mobileNumber, $whatsappAccount, $ctaUrl);
                } elseif ($wooCommerceProduct && $ctaUrlData) {

                    if (!$user->ecommerce_available) {
                        return apiResponse("not_found", "error", ["Your current plan does not e-commerce configured messages. Please upgrade your plan."]);
                    }

                    $messageSend = (new WhatsAppLib())->sendCtaUrlMessage($conversation->contact->mobileNumber, $whatsappAccount, null, $ctaUrlData);
                } elseif ($createdOrderData && $ctaUrlData) {

                    if (!$user->ecommerce_available) {
                        return apiResponse("not_found", "error", ["Your current plan does not e-commerce configured messages. Please upgrade your plan."]);
                    }

                    $messageSend = (new WhatsAppLib())->sendCtaUrlMessage($conversation->contact->mobileNumber, $whatsappAccount, null, $ctaUrlData);
                } else {
                    $messageSend = (new WhatsAppLib())->sendInteractiveListMessage($conversation->contact->mobileNumber, $whatsappAccount, $interactiveList);
                }
            } else {
                $messageSend = (new WhatsAppLib())->messageSend($request, $conversation->contact->mobileNumber, $whatsappAccount,$replyToMessage);
            }

            extract($messageSend);

            $agentId = 0;
            if (auth()->user()->is_agent) $agentId = auth()->id();

            $type = getIntMessageType($messageType);
            $location = null;
            if ($type == Status::LOCATION_TYPE_MESSAGE && $request->latitude && $request->longitude) {
                $location = [
                    'latitude'  => $request->latitude,
                    'longitude' => $request->longitude,
                    'name'      => $request->name ?? "",
                    'address'   => $request->address ?? "",
                ];
            }

            $message                      = new Message();
            $message->user_id             = $user->id;
            $message->whatsapp_account_id = $whatsappAccount->id;
            $message->whatsapp_message_id = $whatsAppMessage[0]['id'];
            $message->reply_to_id         = @$replyToMessage->id ?? 0;
            $message->conversation_id     = $conversation->id;
            $message->cta_url_id          = $ctaUrlId ?? 0;
            $message->interactive_list_id = $interactiveListId ?? 0;
            $message->type                = Status::MESSAGE_SENT;
            $message->message             = $request->message;
            $message->media_id            = $mediaId;
            $message->message_type        = $type;
            $message->location            = $location;
            $message->media_caption       = $mediaCaption;
            $message->media_filename      = $mediaFileName;
            $message->media_url           = $mediaUrl;
            $message->media_path          = $mediaPath;
            $message->mime_type           = $mimeType;
            $message->media_type          = $mediaType;
            $message->agent_id            = $agentId;
            $message->status              = Status::SENT;
            $message->ordering            = Carbon::now();
            $message->product_data        = $productData ?? null;
            $message->save();

            $conversation->last_message_at = Carbon::now();
            $conversation->save();

            $notify[] =  "Message sent successfully";

            if (!isApiRequest()) {
                $lastMessageHtml = view("Template::user.inbox.conversation_last_message", compact('message'))->render();
                return apiResponse("success", "success", $notify, [
                    'conversationId' => $conversation->id,
                    'html' => view('Template::user.inbox.single_message', compact('message'))->render(),
                    'lastMessageHtml' => $lastMessageHtml,
                    'lastMessageAt' => showDateTime($conversation->last_message_at),
                ]);
            }
            return apiResponse("success", "success", $notify, [
                'message' => $message
            ]);
        } catch (Exception $ex) {
            $notify[] =  $ex->getMessage() ?? "Something went to wrong";
            return apiResponse("exception", "error", $notify);
        }
    }

    public function sendTemplateMessage(Request $request)
    {
        $request->validate([
            'conversation_id'  => 'required',
            'template_id'      => 'required',
            'button_variables' => 'nullable|array',
        ]);

        $user = getParentUser();

        $conversation = Conversation::where('user_id', $user->id)->find($request->conversation_id);

        if (!$conversation) {
            return apiResponse("not_found", "error", ["The conversation is not found"]);
        }

        $contact = $conversation->contact;

        if ($contact->is_blocked) {
            return apiResponse("not_found", "error", ["You cannot send or receive messages with this contact because they have been blocked."]);
        }

        if (!$contact) {
            return apiResponse("not_found", "error", ["The contact is not found"]);
        }

        $template  =  Template::where('user_id', $user->id)->approved()->find($request->template_id);

        if (!$template) {
            return apiResponse("not_found", "error", ["The template is not found"]);
        }

        try {
            $whatsappAccount = $user->currentWhatsapp();

            if (!$whatsappAccount) {
                return apiResponse("not_found", "error", ["Please connect your whatsapp account first"]);
            }

            $messageSend = (new WhatsAppLib())->sendTemplateMessage($request, $whatsappAccount, $template, $contact);

            extract($messageSend);

            $agentId = 0;
            if (auth()->user()->is_agent) $agentId = auth()->id();

            $message                      = new Message();
            $message->user_id             = $user->id;
            $message->template_id         = $template->id;
            $message->whatsapp_account_id = $whatsappAccount->id;
            $message->whatsapp_message_id = $whatsAppMessage[0]['id'];
            $message->conversation_id     = $conversation->id;
            $message->type                = Status::MESSAGE_SENT;
            $message->agent_id            = $agentId;
            $message->status              = Status::SENT;
            $message->ordering            = Carbon::now();
            $message->save();

            $conversation->last_message_at = Carbon::now();
            $conversation->save();

            $notify[] =  "Message sent successfully";

            $lastMessageHtml = view("Template::user.inbox.conversation_last_message", compact('message'))->render();
            return apiResponse("success", "success", $notify, [
                'conversationId' => $conversation->id,
                'html' => view('Template::user.inbox.single_message', compact('message'))->render(),
                'lastMessageHtml' => $lastMessageHtml,
                'lastMessageAt' => showDateTime($conversation->last_message_at),
            ]);
        } catch (Exception $ex) {
            $notify[] =  $ex->getMessage() ?? "Something went to wrong";
            return apiResponse("exception", "error", $notify);
        }
    }

    public function resendMessage(Request $request)
    {
        $request->validate([
            'message_id' => 'required',
        ]);

        $user    = getParentUser();

        $message = Message::where('user_id', $user->id)->where('type', Status::SENT)->where('status', Status::FAILED)->find($request->message_id);
        if (!$message) {
            return apiResponse("message_not_found", "error", ["Message not found"]);
        }

        $conversation  = $message->conversation;
        $contact = $conversation->contact;

        if ($contact->is_blocked) {
            return apiResponse("not_found", "error", ["You cannot send or receive messages with this contact because they have been blocked."]);
        }

        if (!$conversation || !$contact) {
            return apiResponse("not_found", "error", ["The receiver does not exist"]);
        }

        $agentId = 0;
        if (auth()->user()->is_agent) $agentId = auth()->id();

        try {
            $whatsappAccount  = $user->currentWhatsapp();
            $messageResend = (new WhatsAppLib())->messageResend($message, $conversation->contact->mobileNumber, $whatsappAccount);

            $message->whatsapp_message_id = $messageResend['whatsAppMessage'][0]['id'];
            $message->status              = Status::MESSAGE_SENT;
            $message->ordering            = Carbon::now();
            $message->agent_id            = $agentId;
            $message->save();


            if (isApiRequest()) {
                return apiResponse("success", "success", ["Message resend successfully"]);
            }
            return apiResponse("success", "success", ["Message resend successfully"], [
                'html' => view('Template::user.inbox.single_message', compact('message'))->render()
            ]);
        } catch (Exception $ex) {
            $notify[] =  $ex->getMessage() ?? "Something went to wrong";
            return apiResponse("exception", "error", $notify);
        }
    }

    public function downloadMedia($mediaId)
    {
        $user = getParentUser();

        $message = Message::where('media_id', $mediaId)
            ->where('user_id', $user->id)
            ->first();

        if (!$message) {
            return apiResponse("message_not_found", "error", ["Message not found"]);
        }

        try {
            if ($message->message_type == Status::IMAGE_TYPE_MESSAGE) {
                if ($message->media_path) {
                    if (s3_configured()) {
                        $fileContent = s3_disk()->get('conversation/' . $message->media_path);
                        $finfo = new \finfo(FILEINFO_MIME_TYPE);
                        $mimeType = $finfo->buffer($fileContent);
                        $extension = pathinfo($message->media_path, PATHINFO_EXTENSION);
                        $fileName = $message->media_id . '.' . $extension;
                        return response($fileContent, 200)
                            ->header('Content-Type', $mimeType)
                            ->header('Content-Disposition', "attachment; filename={$fileName}");
                    } else {
                        $filePath = getFilePath('conversation') . "/" . $message->media_path;
                        if (File::exists($filePath)) {
                            return response()->download($filePath);
                        }
                    }
                }
                return responseManager('exception', "Failed to load the media");
            }

            $whatsapp = $user->currentWhatsapp();
            if (!$whatsapp) {
                return responseManager('exception', "WhatsApp account not found");
            }
            $accessToken = $whatsapp->access_token;

            $mediaUrl = (new WhatsAppLib())
                ->getMediaUrl($mediaId, $accessToken)['url'];

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}",
            ])->get($mediaUrl);

            if ($response->failed()) {
                return responseManager('exception', "Failed to load the media");
            }

            $fileContent = $response->body();
            $mimeType = $response->header('Content-Type');
            $extension = explode('/', $mimeType)[1];
            $fileName = "{$mediaId}.{$extension}";

            return response($fileContent, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', "attachment; filename={$fileName}");
        } catch (Exception $ex) {
            return responseManager('exception', $ex->getMessage());
        }
    }

    public function viewMedia($mediaId)
    {
        $user = getParentUser();

        $message = Message::where('media_id', $mediaId)
            ->where('user_id', $user->id)
            ->first();

        if (!$message) {
            return responseManager('exception', "Message not found");
        }

        try {
            if (in_array($message->message_type, [Status::IMAGE_TYPE_MESSAGE, Status::VIDEO_TYPE_MESSAGE, Status::AUDIO_TYPE_MESSAGE]) && $message->media_path) {
                if (s3_configured()) {
                    $fileContent = s3_disk()->get('conversation/' . $message->media_path);
                } else {
                    $filePath = getFilePath('conversation') . "/" . $message->media_path;
                    $fileContent = file_get_contents($filePath);
                }

                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($fileContent);
                $extension = pathinfo($message->media_path, PATHINFO_EXTENSION);
                $fileName = $message->media_id . '.' . $extension;

                return response($fileContent, 200)
                    ->header('Content-Type', $mimeType)
                    ->header('Content-Disposition', "inline; filename={$fileName}");
            }

            return $this->downloadMedia($mediaId);
        } catch (Exception $ex) {
            return responseManager('exception', $ex->getMessage());
        }
    }

    public function serveMediaByPath($path)
    {
        $message = Message::where('media_path', $path)->first();

        if (!$message || !$message->media_path) {
            return responseManager('exception', "Media not found");
        }

        try {
            if (s3_configured()) {
                $fileContent = s3_disk()->get('conversation/' . $message->media_path);
            } else {
                $filePath = getFilePath('conversation') . "/" . $message->media_path;
                if (!file_exists($filePath)) {
                    return responseManager('exception', "File not found");
                }
                $fileContent = file_get_contents($filePath);
            }

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            return response($fileContent, 200)
                ->header('Content-Type', $finfo->buffer($fileContent))
                ->header('Content-Disposition', "inline");
        } catch (Exception $ex) {
            return responseManager('exception', $ex->getMessage());
        }
    }

    public function generateAiMessage(Request $request)
    {
        $request->validate([
            'message'         => 'required|string',
        ], [
            'message.required' => 'Unable to generate response. Please try again.',
            'message.string'   => 'The only type of message allowed is text.',
        ]);

        $user = getParentUser();

        if (!featureAccessLimitCheck($user->ai_assistance)) {
            return responseManager('not_available', 'Your current plan does not support AI Assistant. Please upgrade your plan.');
        }

        $activeProvider = AiAssistant::active()->first();
        if (!$activeProvider) {
            return responseManager('not_available', 'AI Assistant is currently disabled. Please try again.');
        }

        $userAiSetting  = $user->aiSetting;
        if (!$userAiSetting || !$userAiSetting->status) {
            return responseManager('not_available', 'AI Assistant is disabled');
        }

        $provider = [
            'openai' => OpenAi::class,
            'gemini' => Gemini::class
        ];

        $aiAssistantClass = $provider[$activeProvider->provider];

        $aiAssistant = new $aiAssistantClass();
        $systemPrompt    = $userAiSetting->system_prompt;
        $aiResponse      = $aiAssistant->getAiReply($systemPrompt, $request->message);

        if ($aiResponse['success'] == true) {
            if ($aiResponse['response'] == null) {
                return responseManager('null_response', 'AI Assistant is unable to generate response for this message.');
            } else {
                return responseManager('ai_response', 'Response generated successfully', 'success', [
                    'ai_response' => $aiResponse['response']
                ]);
            }
        } else {
            return responseManager('error', 'Unable to generate AI response. Please try again.');
        }
    }

    public function translateAiMessage(Request $request)
    {
        $request->validate([
            'message'         => 'required|string',
        ], [
            'message.required' => 'Unable to generate response. Please try again.',
            'message.string'   => 'The only type of message allowed is text.',
        ]);

        $user = getParentUser();

        if (!featureAccessLimitCheck($user->ai_assistance)) {
            return responseManager('not_available', 'Your current plan does not support AI Assistant. Please upgrade your plan.');
        }

        $activeProvider = AiAssistant::active()->first();
        if (!$activeProvider) {
            return responseManager('not_available', 'AI Assistant is currently disabled. Please try again.');
        }

        $userAiSetting  = $user->aiSetting;

        if (!$userAiSetting || !$userAiSetting->status) {
            return responseManager('not_available', 'AI Assistant is disabled');
        }

        $provider = [
            'openai' => OpenAi::class,
            'gemini' => Gemini::class
        ];

        $aiAssistantClass = $provider[$activeProvider->provider];

        $aiAssistant = new $aiAssistantClass();
        $aiResponse      = $aiAssistant->getTranslatedText($request->message);


        if ($aiResponse['success'] == true) {
            if ($aiResponse['response'] == null) {
                return responseManager('null_response', 'AI Assistant is unable to translate this message.');
            } else {
                return responseManager('ai_response', 'Response generated successfully', 'success', [
                    'ai_response' => $aiResponse['response']
                ]);
            }
        } else {
            return responseManager('error', 'Unable to generate AI response. Please try again.');
        }
    }
}
