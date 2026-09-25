<?php

namespace App\Traits;

use App\Constants\Status;
use App\Lib\CurlRequest;
use App\Models\Message;
use App\Models\Template;
use App\Models\WhatsappAccount;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

trait WhatsappManager
{
    public function verifyWhatsappCredentials($businessId, $token)
    {
        try {
            $apiUrl = "https://graph.facebook.com/v22.0/{$businessId}/phone_numbers";
            $header  = [
                'Authorization: Bearer ' . $token
            ];

            $response = CurlRequest::curlContent($apiUrl, $header);
            $data     = json_decode($response, true);
            if (!is_array($data) || !isset($data['data']) || isset($data['error'])) {
                throw new Exception($data['error']['message'] ?? 'Invalid WhatsApp business credentials. Please check your credentials and try again.');
            }

            return [
                'success' => true,
                'data'    => $data['data'][0] ?? null,
            ];
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage() ?? "Something went wrong");
        }
    }

    public function sendWelcomeMessage($whatsappAccount, $user, $contact, $conversation)
    {
        $welcomeMessage = $whatsappAccount->welcomeMessage;

        if (!$welcomeMessage) return;

        $lockKey        = "welcome_message_sent:{$conversation->user_id}:{$conversation->contact_id}";

        if (!cache()->add($lockKey, true, 10)) {
            return;
        }

        $url = "https://graph.facebook.com/v22.0/{$whatsappAccount->phone_number_id}/messages";

        $header = [
            'Authorization: Bearer ' . $whatsappAccount->access_token
        ];

        $response = CurlRequest::curlPostContent($url, [
            'messaging_product' => 'whatsapp',
            'to'                => $contact->mobileNumber,
            'type'              => 'text',
            'text'              => [
                'preview_url' => false,
                'body'        => $welcomeMessage->message,
            ],
        ], $header);

        $data = json_decode($response, true);

        if (isset($data['error']) || !is_array($data)) {
            return [
                'success' => false,
                'message' => $data['error']['error_user_msg'] ?? $data['error']['message'],
                'data' => null,
            ];
        };

        $message                      = new Message();
        $message->user_id             = $user->id;
        $message->whatsapp_account_id = $whatsappAccount->id;
        $message->whatsapp_message_id = $data['messages'][0]['id'];
        $message->conversation_id     = $conversation->id;
        $message->type                = Status::MESSAGE_SENT;
        $message->message             = $welcomeMessage->message;
        $message->ordering            = Carbon::now();
        $message->save();

        $conversation->last_message_at = Carbon::now();
        $conversation->save();

        cache()->forget($lockKey);
    }

    public function templateUpdateNotify($templateId, $event, $reason = null)
    {
        $template = Template::where('whatsapp_template_id', (string)$templateId)->first();
        if (!$template) return;

        $user     = $template->user;
        if (!$user) return;

        if ($event == 'APPROVED') {
            $template->status = metaTemplateStatus($event);
            $template->save();

            notify($user, 'TEMPLATE_APPROVED', [
                'name'        => $template->name,
                'template_id' => $template->id,
                'time'        => showDateTime(Carbon::now()),
                'reason'      => $reason ?? ''
            ]);
        }
        if ($event == 'REJECTED') {
            $template->status = metaTemplateStatus($event);
            $template->rejected_reason = $reason;
            $template->save();

            notify($user, 'TEMPLATE_REJECTED', [
                'name'        => $template->name,
                'template_id' => $template->id,
                'time'        => showDateTime(Carbon::now()),
                'reason'      => $reason ?? ''
            ]);
        }
    }

    public function sendTestMessage(Request $request, $id)
    {
        $request->validate([
            'phone_number' => 'required|string',
            'message'      => 'required|string',
        ]);

        $whatsappAccount = WhatsappAccount::where('user_id', getParentUser()->id)->find($id);

        if (!$whatsappAccount) {
            return apiResponse("not_found", "error", ["The whatsapp account is not found"]);
        }

        $url    = "https://graph.facebook.com/v22.0/{$whatsappAccount->phone_number_id}/messages";

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$whatsappAccount->access_token}",
            ])->post($url, [
                'messaging_product' => 'whatsapp',
                'recipient_type'    => 'individual',
                'to'                => $request->phone_number,
                'type'              => 'text',
                'text'              => [
                    'body' => $request->message
                ]
            ]);
            if ($response->failed()) {
                $responseData = $response->json();
                $errorMessage =  ["Something went wrong, please try later"];

                if (isset($responseData['error']) || !isset($responseData['messages'])) {
                    $errorMessage = @$responseData['error']['message'] ?? ["Something went wrong"];
                    return apiResponse("error", "error", [$errorMessage]);
                }

                return apiResponse('error', 'error', $errorMessage);
            }
            return apiResponse("success", "success", ["Test Message sent successfully"]);
        } catch (\Exception $e) {
            return apiResponse(
                'error',
                'error',
                ['An unexpected error occurred while sending the test message. Please try again later.']
            );
        }
    }

    public function accountDelete($id)
    {
        $parentUser = getParentUser();
        $whatsappAccount = WhatsappAccount::where('user_id', $parentUser->id)->findOrFail($id);
        $messageCount    = Message::where('whatsapp_account_id', $whatsappAccount->id)->count();
        $templateCount   = Template::where('whatsapp_account_id', $whatsappAccount->id)->count();

        if ($messageCount > 0) {
            $notify[] = ['error', 'This account is not deleted because it has ' . $messageCount . ' messages.'];
            return back()->withNotify($notify);
        }
        if ($templateCount > 0) {
            $notify[] = ['error', 'This account is not deleted because it has ' . $templateCount . ' templates.'];
            return back()->withNotify($notify);
        }

        $whatsappAccount->delete();
        $notify[] = ['success', 'Account deleted successfully'];

        $parentUser->account_limit += 1;
        $parentUser->save();

        return back()->withNotify($notify);
    }
    public function connectWebhook($id)
    {
        $parentUser = getParentUser();
        $whatsappAccount = WhatsappAccount::where('user_id', $parentUser->id)->findOrFail($id);

        $token = $whatsappAccount->access_token;
        $wabId = $whatsappAccount->whatsapp_business_account_id;

        try {
            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ])->post("https://graph.facebook.com/v25.0/$wabId/subscribed_apps", [
                'override_callback_uri' => route('webhook'),
                "verify_token"          => gs('webhook_verify_token')
            ]);

            if ($response->successful()) {
                $message[] = ['info', 'Please note that the following webhook events must be manually enabled by you from the Meta Dashboard: messages, message_template_status_update, messaging_handovers, and message_echoes'];
                $message[] = ['success', 'Webhook connected successfully'];

                return back()->withNotify($message);
            } else {
                $message[] = ['error', "Webhook connection failed."];
                $message[] = ['error', "Kindly try it directly from the Meta Dashboard or try again later."];

                return back()->withNotify($message);
            }
        } catch (Exception $ex) {
            $message[] = ['error', "Webhook connection failed."];
            $message[] = ['error', "Kindly try it directly from the Meta Dashboard or try again later."];
            return back()->withNotify($message);
        }
    }
}
