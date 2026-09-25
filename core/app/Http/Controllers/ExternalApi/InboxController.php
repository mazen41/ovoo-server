<?php

namespace App\Http\Controllers\ExternalApi;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\CurlRequest;
use App\Lib\WhatsApp\WhatsAppLib;
use App\Models\Contact;
use App\Models\ContactNote;
use App\Models\Conversation;
use App\Models\CtaUrl;
use App\Models\InteractiveList;
use App\Models\Message;
use App\Models\Template;
use App\Models\WhatsappAccount;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class InboxController extends Controller
{
    public function list(Request $request)
    {

        $user = getUserFromExternalAPIAccess();
        if (!$user) return apiResponse('user_error', 'error', ['No user found for provided credentials']);

        $conversation = Conversation::where('user_id', $user->id)
            ->latest('last_message_at')
            ->searchable(['contact:firstname,lastname,mobile'])
            ->filter(['status'])
            ->with(['contact', 'notes'])
            ->paginate(getPaginate());

        return apiResponse('inbox', 'success', ['Conversation data fetched successfully'], [
            'conversation'      => $conversation ?? [],
        ]);
    }
    public function templateList()
    {
        $user = getUserFromExternalAPIAccess();
        if (!$user) return apiResponse('user_error', 'error', ['No user found for provided credentials']);

        $templates = Template::where('user_id', $user->id)
            ->paginate(getPaginate());

        return apiResponse('inbox', 'success', ['Template data fetched successfully'], [
            'templates' => $templates,
        ]);
    }

    public function conversationMessages($conversationId)
    {
        $user = getUserFromExternalAPIAccess();
        if (!$user) return apiResponse('user_error', 'error', ['No user found for provided credentials']);

        $conversation = Conversation::where('user_id', $user->id)->where('id', $conversationId)->with('contact')->first();
        if (!$conversation) return apiResponse('not_found', 'error', ['Conversation not found']);

        $messageQuery = Message::where('conversation_id', $conversationId);
        $messages     = $messageQuery->orderBy('ordering', 'desc')->paginate(getPaginate());

        return apiResponse('chat_messages', 'success', ['Conversation messages fetched successfully.'], [
            'messages' => $messages,
        ]);
    }

    public function updateMessageStatus($id)
    {
        $user = getUserFromExternalAPIAccess();
        if (!$user) return apiResponse('user_error', 'error', ['No user found for provided credentials']);

        $conversation = Conversation::where('user_id', $user->id)->where('id', $id)->first();
        if (!$conversation) return apiResponse('not_found', 'error', ['Conversation not found']);

        $whatsapp = WhatsappAccount::where('user_id', $user->id)->where('id', $conversation->whatsapp_account_id)->first();
        if (!$whatsapp) return apiResponse('not_found', 'error', ['Whatsapp account not found']);

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

        return apiResponse('status_changed', 'success', ['Status changed successfully'], [
            'unseen_message_count' => $conversation->unseenMessages()->count()
        ]);
    }

    public function changeConversationStatus(Request $request, $conversationId)
    {
        $user = getUserFromExternalAPIAccess();
        if (!$user) return apiResponse('user_error', 'error', ['No user found for provided credentials']);


        $validator = Validator::make($request->all(), [
            'status' => ['required', "integer", Rule::in([Status::DONE_CONVERSATION, Status::PENDING_CONVERSATION, Status::IMPORTANT_CONVERSATION, 0])]
        ]);

        if ($validator->fails()) return apiResponse('validation_error', 'error', $validator->errors()->all());

        $conversation = Conversation::where('user_id', $user->id)->where('id', $conversationId)->first();
        if (!$conversation)  return apiResponse("not_found", "error", ["Conversation not found"]);

        $conversation->status = $request->status ?? 0;
        $conversation->save();

        


        return apiResponse("status_updated", "success", ["Status updated successfully"]);
    }

    public function conversationDetails($conversationId)
    {
        $user = getUserFromExternalAPIAccess();
        if (!$user) return apiResponse('user_error', 'error', ['No user found for provided credentials']);


        $conversation = Conversation::where('user_id', $user->id)->where('id', $conversationId)->with(['contact', 'notes', 'contact.tags', 'contact.lists'])->first();
        if (!$conversation) return apiResponse('not_found', 'error', ['Conversation not found']);

        return apiResponse('conversation_details', 'success', ['Conversation details fetched successfully.'], [
            'conversation' => $conversation,
        ]);
    }



    public function sendMessage(Request $request)
    {
        $user = getUserFromExternalAPIAccess();

        if (!$user) return apiResponse('user_error', 'error', ['No user found for provided credentials']);

        $validator = Validator::make($request->all(), [
            'message'             => 'required_without_all:image,document,video,audio,cta_url_id,latitude,longitude,interactive_list_id,product,created_order_data',
            'image'               => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:5120'],
            'document'            => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:102400'],
            'video'               => ['nullable', 'file', 'mimes:mp4', 'max:16384'],
            'audio'               => 'nullable|file|max:16384',
            'cta_url_id'          => 'nullable|int',
            'interactive_list_id' => 'nullable|int',
            'latitude'            => 'required_if:longitude,!=,0',
            'longitude'           => 'required_if:latitude,!=,0',
            'mobile_code'         => 'required',
            'mobile'              => ['required', 'regex:/^([0-9]*)$/'],
            'from_number'         => ['nullable']
        ], [
            'conversation_id.required'     => 'Please select a conversation to send message.',
            'message.required_without_all' => 'The message should not be empty.',
            'longitude.required_if'        => 'The longitude field is required when latitude is not empty.',
        ]);

        if ($validator->fails()) return apiResponse('validation_error', 'error', $validator->errors()->all());

        if ($request->from_number) {
            $whatsappAccount  = WhatsappAccount::where('user_id', $user->id)->where('phone_number', $request->from_number)->first();
        } else {
            $whatsappAccount  = WhatsappAccount::where('user_id', $user->id)->first();
        }

        if (!$whatsappAccount) return apiResponse("not_found", "error", ["The whatsapp account is not found"]);

        $hasProduct   = !empty($request->product);
        $hasCreated   = !empty($request->created_order_data);
        $hasCtaId     = !empty($request->cta_url_id);
        $hasListId    = !empty($request->interactive_list_id);

        $contact = Contact::where('user_id', $user->id)->where('mobile_code', $request->mobile_code)->where('mobile', $request->mobile)->first();

        if (!$contact) {

            $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
            $foundCountry = null;
            foreach ($countries as $countryCode => $countryData) {
                if (isset($countryData->dial_code) && $countryData->dial_code == $request->mobile_code) {
                    $foundCountry = $countryData;
                    break;
                }
            }
            $contact              = new Contact();
            $contact->user_id     = $user->id;
            $contact->mobile_code = $request->mobile_code;
            $contact->mobile      = $request->mobile;
            $contact->address     = [
                'city'      => '',
                'state'     => '',
                'post_code' => '',
                'address'   => '',
                'country'   => $foundCountry->country ?? ''
            ];
        }


        $conversation = Conversation::where('user_id', $user->id)->where('contact_id', $contact->id)->first();

        if (!$conversation) {
            $conversation             = new Conversation();
            $conversation->contact_id = $contact->id;
            $conversation->user_id    = $user->id;
            $conversation->save();
        }

        if ($contact->is_blocked) return apiResponse("not_found", "error", ["You cannot send or receive messages with this contact because they have been blocked."]);

        $ctaUrl          = null;
        $interactiveList = null;
        $ctaUrlData      = null;

        if ($hasCtaId) {
            $ctaUrl = CtaUrl::where('user_id', $user->id)->where('id', $request->cta_url_id)->first();
            if (!$ctaUrl) return apiResponse("not_found", "error", ["The cta url is not found"]);
        }

        if ($hasListId) {
            $interactiveList = InteractiveList::where('user_id', $user->id)->find($request->interactive_list_id);
            if (!$interactiveList) return apiResponse("not_found", "error", ["The interactive list is not found"]);
        }

        $wooCommerceProduct = $hasProduct ? json_decode($request->product, true) : null;
        $createdOrderData   = $hasCreated ? json_decode($request->created_order_data, true) : null;

        try {
            if ($wooCommerceProduct) {
                $ctaUrlData = makeCtaUrlFromProduct($wooCommerceProduct);
            }

            if ($createdOrderData) {
                $ctaUrlData = makeCtaUrlFromCreatedOrder($createdOrderData);
            }

            $contactNumber = $conversation->contact->mobileNumber;
            $waService     = new WhatsAppLib();

            if ($ctaUrl || $interactiveList || $ctaUrlData) {
                if (!featureAccessLimitCheck($user->interactive_message)) {
                    return apiResponse("not_found", "error", [
                        "Your current plan does not support interactive messages. Please upgrade your plan."
                    ]);
                }

                if ($ctaUrl) {
                    $messageSend = $waService->sendCtaUrlMessage($contactNumber, $whatsappAccount, $ctaUrl);
                } elseif ($ctaUrlData) {
                    if (!$user->ecommerce_available) {
                        return apiResponse("not_found", "error", [
                            "Your current plan does not support e-commerce configured messages. Please upgrade your plan."
                        ]);
                    }

                    $messageSend = $waService->sendCtaUrlMessage($contactNumber, $whatsappAccount, null, $ctaUrlData);
                } else {
                    $messageSend = $waService->sendInteractiveListMessage($contactNumber, $whatsappAccount, $interactiveList);
                }
            } else {
                $messageSend = $waService->messageSend($request, $contactNumber, $whatsappAccount);
            }

            extract($messageSend);

            $agentId = $user->is_agent ? $user->id : 0;
            $type    = getIntMessageType($messageType);

            $location = ($type == Status::LOCATION_TYPE_MESSAGE && $request->latitude && $request->longitude)
                ? [
                    'latitude'  => $request->latitude,
                    'longitude' => $request->longitude,
                    'name'      => $request->name ?? "",
                    'address'   => $request->address ?? "",
                ]
                : null;

            $message                      = new Message();
            $message->user_id             = $user->id;
            $message->whatsapp_account_id = $whatsappAccount->id;
            $message->whatsapp_message_id = $whatsAppMessage[0]['id'];
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

            return apiResponse("success", "success", $notify, [
                'conversation_id' => $conversation->id,
                'message'         => $message
            ]);
        } catch (Exception $ex) {
            $notify[] =  $ex->getMessage() ?? "Something went to wrong";
            return apiResponse("exception", "error", $notify);
        }
    }

    public function sendTemplateMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'template_id'     => 'required',
            'mobile_code'         => 'required',
            'mobile'              => ['required', 'regex:/^([0-9]*)$/'],
            'from_number'         => ['nullable']
        ]);

        if ($validator->fails()) return apiResponse('validation_error', 'error', $validator->errors()->all());

        $user = getUserFromExternalAPIAccess();
        if (!$user) return apiResponse('user_error', 'error', ['No user found for provided credentials']);

        if ($request->from_number) {
            $whatsappAccount  = WhatsappAccount::where('user_id', $user->id)->where('phone_number', $request->from_number)->first();
        } else {
            $whatsappAccount  = WhatsappAccount::where('user_id', $user->id)->first();
        }



        $contact = Contact::where('user_id', $user->id)->where('mobile_code', $request->mobile_code)->where('mobile', $request->mobile)->first();

        if (!$contact) {
            $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
            $foundCountry = null;
            foreach ($countries as $countryCode => $countryData) {
                if (isset($countryData->dial_code) && $countryData->dial_code == $request->mobile_code) {
                    $foundCountry = $countryData;
                    break;
                }
            }
            $contact              = new Contact();
            $contact->user_id     = $user->id;
            $contact->mobile_code = $request->mobile_code;
            $contact->mobile      = $request->mobile;
            $contact->address     = [
                'city'      => '',
                'state'     => '',
                'post_code' => '',
                'address'   => '',
                'country'   => $foundCountry->country ?? ''
            ];
        }


        $conversation = Conversation::where('user_id', $user->id)->where('contact_id', $contact->id)->first();

        if (!$conversation) {
            $conversation             = new Conversation();
            $conversation->contact_id = $contact->id;
            $conversation->user_id    = $user->id;
            $conversation->save();
        }



        if (!$conversation) return apiResponse("not_found", "error", ["The conversation is not found"]);

        if ($contact->is_blocked) return apiResponse("not_found", "error", ["You cannot send or receive messages with this contact because they have been blocked."]);

        $template  =  Template::where('user_id', $user->id)->where('id', $request->template_id)->approved()->first();
        if (!$template) return apiResponse("not_found", "error", ["The template is not found"]);

        try {
            $whatsappAccount = $user->currentWhatsapp();
            if (!$whatsappAccount) return apiResponse("not_found", "error", ["Please connect your whatsapp account first"]);

            $messageSend = (new WhatsAppLib())->sendTemplateMessage($request, $whatsappAccount, $template, $contact);

            extract($messageSend);

            $agentId = $user->is_agent ? $user->id : 0;

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

            $conversation->last_message_at   = Carbon::now();
            $conversation->save();

            $notify[] = 'Message sent successfully';

            return apiResponse('template_sent', 'success', $notify, [
                'conversation_id' => $conversationId
            ]);;
        } catch (\Exception $exp) {
            return apiResponse("exception", "error", [$exp->getMessage() ?? 'Something went to wrong']);
        }
    }
}
