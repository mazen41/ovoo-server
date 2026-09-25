<?php

namespace App\Lib\WhatsApp;

use App\Constants\Status;
use App\Events\ReceiveMessage;
use App\Lib\AiAssistantLib\Gemini;
use App\Lib\AiAssistantLib\OpenAi;
use App\Lib\CurlRequest;
use App\Models\AiAssistant;
use App\Models\Message;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class WhatsAppLib
{
    public function sendInteractiveListMessage($toNumber, $whatsappAccount, $interactiveList)
    {
        $phoneNumberId    = $whatsappAccount->phone_number_id;
        $accessToken      = $whatsappAccount->access_token;

        $url       = $this->getWhatsAppBaseUrl() . "{$phoneNumberId}/messages";

        $data = [
            'messaging_product' => 'whatsapp',
            'recipient_type'    => 'individual',
            'to'                => $toNumber,
            'type'              => 'interactive'
        ];

        $list = [
            'type'  => 'list',
        ];

        if (isset($interactiveList->header) && !empty($interactiveList->header)) {
            $list['header'] = $interactiveList->header;
        }

        $list['body'] = $interactiveList->body;

        if (isset($interactiveList->footer) && !empty($interactiveList->footer)) {
            $list['footer'] = $interactiveList->footer;
        }

        $list['action'] = [
            'button' => $interactiveList->button_text,
            'sections' => $interactiveList->sections
        ];

        $data['interactive'] = $list;

        try {

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}"
            ])->post($url, $data);

            $responseData = $response->json();

            if (!is_array($responseData) || !count($responseData)) {
                throw new Exception("Something went wrong");
            }

            if (isset($responseData['error']) || !isset($responseData['messages'])) {
                throw new Exception(@$responseData['error']['error_data']['details'] ?? @$responseData['error']['message'] ?? "Something went wrong");
            }

            if ($response->failed()) {
                throw new Exception("Message sending failed");
            }

            return [
                'whatsAppMessage'   => $responseData['messages'],
                'interactiveListId' => $interactiveList->id,
                'mediaId'           => null,
                'mediaUrl'          => null,
                'mediaPath'         => null,
                'mediaCaption'      => null,
                'mediaFileName'     => null,
                'messageType'       => 'list',
                'mimeType'          => null,
                'mediaType'         => null
            ];
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function sendCtaUrlMessage($toNumber, $whatsappAccount, $ctaUrl = null, $productData = null)
    {
        $phoneNumberId    = $whatsappAccount->phone_number_id;
        $accessToken      = $whatsappAccount->access_token;

        $url       = $this->getWhatsAppBaseUrl() . "{$phoneNumberId}/messages";

        $data = [
            'messaging_product' => 'whatsapp',
            'recipient_type'    => 'individual',
            'to'                => $toNumber,
            'type'              => 'interactive'
        ];

        if ($ctaUrl) {
            $interactive = [
                'type'  => 'cta_url',
                'header' => $ctaUrl->header,
                'body'  => $ctaUrl->body,
                'action' => $ctaUrl->action
            ];

            if (!empty($ctaUrl->footer) && count($ctaUrl->footer) > 0) {
                $interactive['footer'] = $ctaUrl->footer;
            }
        }

        if ($productData) {
            $data['interactive'] = $productData;
        } else {
            $data['interactive'] = $interactive;
        }

        try {

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}"
            ])->post($url, $data);
            $responseData = $response->json();

            if (!is_array($responseData) || !count($responseData)) {
                throw new Exception("Something went wrong");
            }

            if (isset($responseData['error']) || !isset($responseData['messages'])) {
                throw new Exception(@$responseData['error']['error_data']['details'] ?? @$responseData['error']['message'] ?? "Something went wrong");
            }

            if ($response->failed()) {
                throw new Exception("Message sending failed");
            }

            return [
                'whatsAppMessage' => $responseData['messages'],
                'ctaUrlId'        => $ctaUrl->id ?? null,
                'productData'     => $productData,
                'mediaId'         => null,
                'mediaUrl'        => null,
                'mediaPath'       => null,
                'mediaCaption'    => null,
                'mediaFileName'   => null,
                'messageType'     => 'url',
                'mimeType'        => null,
                'mediaType'       => null
            ];
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function sendTemplateMessage($request, $whatsappAccount, $template, $contact)
    {
        $phoneNumberId = $whatsappAccount->phone_number_id;
        $accessToken   = $whatsappAccount->access_token;

        $bodyParams = [];
        $headerParams = [];

        foreach (($request->body_variables ?? []) as $value) {
            $bodyParams[] = [
                'type' => 'text',
                'text' => $value ?? '',
            ];
        }

        foreach (($request->header_variables ?? []) as $value) {
            $headerParams[] = [
                'type' => 'text',
                'text' => $value ?? '',
            ];
        }

        $templateHeaderParams = parseTemplateParams($headerParams, $contact);
        $templateBodyParams   = parseTemplateParams($bodyParams, $contact);

        // If the caller didn't supply body variables but the template body
        // has {{n}} placeholders, auto-fill them so the send never fails on
        // an empty/mismatched body component (no user input required).
        if (empty($templateBodyParams) && !empty($template->body)) {
            $bodyText = is_array($template->body) ? ($template->body['text'] ?? '') : ($template->body ?? '');
            $requiredBodyParamCount = function_exists('templateBodyParams') ? templateBodyParams($bodyText) : 0;
            for ($i = 0; $i < $requiredBodyParamCount; $i++) {
                $templateBodyParams[] = [
                    'type' => 'text',
                    'text' => $contact->fullName ?? 'Customer',
                ];
            }
        }

        $components = [];

        if (count($template->cards) == 0) {
            $headerFormat = strtoupper($template->header_format ?? '');

            if (is_array($templateHeaderParams) && count($templateHeaderParams)) {
                // Caller supplied dynamic header text variables (TEXT header with {{n}}).
                $components[] = [
                    'type' => 'header',
                    'parameters' => $templateHeaderParams
                ];
            } elseif (in_array($headerFormat, ['IMAGE', 'VIDEO', 'DOCUMENT']) && !empty($template->header_media)) {
                // Media headers (IMAGE/VIDEO/DOCUMENT) always require a header
                // component with a link to the stored media, regardless of
                // whether any variables were passed in.
                $mediaKey  = strtolower($headerFormat);
                $mediaLink = url(getFilePath('templateHeader') . '/' . $template->header_media);

                $headerParameter = [
                    'type'   => $mediaKey,
                    $mediaKey => [
                        'link' => $mediaLink,
                    ],
                ];

                if ($mediaKey === 'document') {
                    $headerParameter['document']['filename'] = $template->header_media;
                }

                $components[] = [
                    'type' => 'header',
                    'parameters' => [$headerParameter],
                ];
            } elseif ($headerFormat === 'TEXT') {
                // TEXT header — always supply a parameter so Meta never gets an
                // empty header component. Priority: stored example value → contact
                // name → generic fallback.
                $headerText = $template->header['example']['header_text'][0]
                    ?? $contact->fullName
                    ?? 'Customer';

                $components[] = [
                    'type' => 'header',
                    'parameters' => [[
                        'type' => 'text',
                        'text' => $headerText,
                    ]],
                ];
            }
        }

        if (is_array($templateBodyParams) && count($templateBodyParams)) {
            $components[] = [
                'type' => 'body',
                'parameters' => $templateBodyParams
            ];
        } else {
            $components[] = [
                'type' => 'body',
                'parameters' => []
            ];
        }

        if (!empty($template->cards) && count($template->cards) > 0) {
            $cards = [];

            foreach ($template->cards as $index => $card) {
                $cardData = [];
                $cardData['card_index'] = $index;
                $cardData['components'] = [];

                if ($card->header_format == 'IMAGE') {
                    $cardData['components'][] = [
                        'type' => 'header',
                        'parameters' => [
                            [
                                'type' => 'image',
                                'image' => [
                                    'id' => $card->media_id
                                ]
                            ]
                        ]
                    ];
                }

                if ($card->buttons && count($card->buttons) > 0) {
                    $cardButtons = [];
                    foreach ($card->buttons['buttons'] as $button) {
                        if ($button['type'] == 'URL') {
                            $cardButtons[] = [
                                'type' => 'button',
                                'sub_type' => strtolower($button['type']),
                                'index' => $index
                            ];
                        }
                    }
                    $cardData['components'] = array_merge($cardData['components'], $cardButtons);
                }

                $cards[] = $cardData;
            }

            $secondParams = [
                'type' => 'carousel',
                'cards' => $cards
            ];

            $components[] = $secondParams;
        }

        $data = [
            'messaging_product' => 'whatsapp',
            'to' => '+' . $contact->mobileNumber,
            'type' => 'template',
            'template' => [
                'name' => trim($template->name),
                'language' => [
                    'code' => $template->language->code,
                ],
                'components' => $components
            ],
        ];

        try {
            $url   = $this->getWhatsAppBaseUrl() . "{$phoneNumberId}/messages?access_token={$accessToken}";
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$whatsappAccount->access_token}",
            ])->post($url, $data);

            $data = $response->json();

            if ($response->failed() || !is_array($data) || !count($data)) {
                throw new Exception($data['error']['error_data']['details'] ?? $data['error']['message'] ?? "Message sending failed");
            }

            return [
                'whatsAppMessage' => $data['messages']
            ];
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage() ?? "Something went wrong");
        }
    }

    public function messageSend($request, $toNumber, $whatsappAccount)
    {

        $phoneNumberId    = $whatsappAccount->phone_number_id;
        $accessToken      = $whatsappAccount->access_token;

        $url       = $this->getWhatsAppBaseUrl() . "{$phoneNumberId}/messages";
        $mediaLink = $this->getWhatsAppBaseUrl() . "{$phoneNumberId}/media";

        $mediaId       = null;
        $mediaUrl      = null;
        $mediaPath     = null;
        $mediaCaption  = null;
        $mediaFileName = null;
        $mimeType      = null;
        $mediaType     = null;

        $data = [
            'messaging_product' => 'whatsapp',
            'recipient_type'    => 'individual',
            'to'                => $toNumber,
        ];

        if ($request->hasFile('image') || $request->get('image') instanceof UploadedFile) {

            $file          = $request->file('image') ?: $request->get('image');
            $mediaUpload   = $this->uploadMedia($mediaLink, $file, $accessToken);

            $mediaId       = $mediaUpload['id'];
            $mediaCaption  = $request->message;
            $data['type']  = 'image';
            $data['image'] = [
                'id'      => $mediaId,
                'caption' => $mediaCaption
            ];
            $mediaType     = 'image';
            $mimeType      = mime_content_type($file->getPathname());
        } else if ($request->hasFile('document') || $request->get('document') instanceof UploadedFile) {
            $file             = $request->file('document') ?: $request->get('document');
            $mediaUpload      = $this->uploadMedia($mediaLink, $file, $accessToken);
            $mediaId          = $mediaUpload['id'];
            $mediaCaption     = $request->message;
            $mediaFileName    = $request->file('document') ? $request->file('document')->getClientOriginalName() : basename($request->get('document'));
            $data['type']     = 'document';
            $data['document'] = [
                'id'       => $mediaId,
                'caption'  => $mediaCaption,
                'filename' => $mediaFileName
            ];
            $mediaType        = 'document';
            $mimeType         = mime_content_type($file->getPathname());
        } else if ($request->hasFile('video') || $request->get('video') instanceof UploadedFile) {
            $file          = $request->file('video') ?: $request->get('video');
            $mediaUpload   = $this->uploadMedia($mediaLink, $file, $accessToken);
            $mediaId       = $mediaUpload['id'];
            $mediaCaption  = $request->message;
            $data['type']  = 'video';
            $data['video'] = [
                'id'      => $mediaId,
                'caption' => $mediaCaption
            ];
            $mediaType     = 'video';
            $mimeType      = 'video/mp4'; // Normalised — WhatsApp only accepts video/mp4
        } else if ($request->hasFile('audio') || $request->get('audio') instanceof UploadedFile) {
            $file          = $request->file('audio') ?: $request->get('audio');
            $mediaUpload   = $this->uploadMedia($mediaLink, $file, $accessToken);
            $mediaId       = $mediaUpload['id'];
            $mediaCaption  = $request->message;
            $data['type']  = 'audio';
            $data['audio'] = [
                'id'      => $mediaId
            ];
            $mediaType     = 'audio';
            $mimeType      = mime_content_type($file->getPathname());
        } else if ($request->latitude && $request->longitude) {
            $data['type'] = 'location';
            $location = [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ];
            if ($request->name != null && $request->address != null) {
                $location['name'] = $request->name;
                $location['address'] = $request->address;
            };

            $data['location'] = $location;
        } else {
            $data['type'] = 'text';
            $data['text'] = [
                'body' => $request->message
            ];
        }

        try {

            if ($mediaId) {
                $mediaUrl = $this->getMediaUrl($mediaId, $accessToken)['url'];
            }

            if ($mediaId && $mediaUrl && ($request->hasFile('image') || $request->get('image') instanceof UploadedFile || $request->hasFile('audio') || $request->get('audio') instanceof UploadedFile || $request->hasFile('video') || $request->get('video') instanceof UploadedFile)) {
                $mediaPath = $this->storedMediaToLocal($mediaUrl, $mediaId, $accessToken, $whatsappAccount->user_id);
            }
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}"
            ])->post($url, $data);

            $responseData = $response->json();
            if (!is_array($responseData) || !count($responseData)) {
                throw new Exception("Something went wrong");
            }

            if (isset($responseData['error']) || !isset($responseData['messages'])) {
                throw new Exception(@$responseData['error']['message'] ?? "Something went wrong");
            }

            if ($response->failed()) {
                throw new Exception("Message sending failed");
            }

            return [
                'whatsAppMessage' => $responseData['messages'],
                'ctaUrlId'        => 0,
                'mediaId'         => $mediaId,
                'mediaUrl'        => $mediaUrl,
                'mediaPath'       => $mediaPath,
                'mediaCaption'    => $mediaCaption,
                'mediaFileName'   => $mediaFileName,
                'messageType'     => $data['type'],
                'mimeType'        => $mimeType ?? null,
                'mediaType'       => $mediaType ?? null,
                'location'        => $data['location'] ?? null
            ];
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function messageResend(object $message, $toNumber, $whatsappAccount)
    {
        $phoneNumberId    = $whatsappAccount->phone_number_id;
        $accessToken      = $whatsappAccount->access_token;

        $url       = $this->getWhatsAppBaseUrl() . "{$phoneNumberId}/messages";

        $mediaId = $message->media_id ?? null;
        $mediaCaption = $message->media_caption ?? null;
        $mediaFileName = $message->media_filename ?? null;

        $data = [
            'messaging_product' => 'whatsapp',
            'to'                => $toNumber,
            'type'              => 'text'
        ];

        if ($message->media_id && $message->message_type == Status::IMAGE_TYPE_MESSAGE) {
            $data['type']  = 'image';
            $data['image'] = [
                'id'      => $mediaId,
                'caption' => $mediaCaption
            ];
        } else if ($message->media_id && $message->message_type == Status::DOCUMENT_TYPE_MESSAGE) {
            $data['type']     = 'document';
            $data['document'] = [
                'id'       => $mediaId,
                'caption'  => $mediaCaption,
                'filename' => $mediaFileName
            ];
        } else if ($message->media_id && $message->message_type == Status::VIDEO_TYPE_MESSAGE) {
            $data['type']  = 'video';
            $data['video'] = [
                'id'      => $mediaId,
                'caption' => $mediaCaption
            ];
        } else {
            $data['type'] = 'text';
            $data['text'] = [
                'body' => $message->message
            ];
        }

        try {

            $response = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}"
            ])->post($url, $data);

            $responseData = $response->json();

            if (!is_array($responseData) || !count($responseData)) {
                throw new Exception("Something went wrong");
            }

            if (isset($responseData['error']) || !isset($responseData['messages'])) {
                throw new Exception(@$responseData['error']['message'] ?? "Something went wrong");
            }

            if ($response->failed()) {
                throw new Exception("Message sending failed");
            }

            return [
                'whatsAppMessage' => $responseData['messages']
            ];
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function uploadMedia($mediaUrl, $file, $accessToken,$isAudio = false)
    {
        if ($file instanceof \Illuminate\Http\UploadedFile) {
            $filePath = $file->getRealPath();
            $fileName = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
        } elseif ($file instanceof UploadedFile) {
            $filePath = $file->getPathname();
            $fileName = basename($filePath);
            $mimeType = mime_content_type($filePath);
        } elseif (is_string($file) && file_exists($file)) {
            $filePath = $file;
            $fileName = basename($file);
            $mimeType = mime_content_type($filePath);
        } else {
            throw new Exception('Invalid file provided for upload');
        }

        // Normalize MIME types to WhatsApp-accepted values.
        // mime_content_type() can return 'video/quicktime' for .mp4 files;
        // WhatsApp only accepts 'video/mp4'.
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        if (in_array($ext, ['mp4', 'mov'])) {
            $mimeType = 'video/mp4';
        } elseif (in_array($ext, ['jpg', 'jpeg'])) {
            $mimeType = 'image/jpeg';
        } elseif ($ext === 'png') {
            $mimeType = 'image/png';
        } elseif ($ext === 'webp') {
            $mimeType = 'image/webp';
        } elseif (in_array($ext, ['ogg', 'opus'])) {
            $mimeType = 'audio/ogg; codecs=opus';
        } elseif ($ext === 'aac') {
            $mimeType = 'audio/aac';
        } elseif ($ext === 'mp3') {
            $mimeType = 'audio/mpeg';
        } elseif ($ext === 'amr') {
            $mimeType = 'audio/amr';
        }

        $response = Http::withToken($accessToken)
            ->attach(
                'file',
                file_get_contents($filePath),
                $fileName,
                ['Content-Type' => $mimeType]
            )
            ->post($mediaUrl, [
                'messaging_product' => 'whatsapp',
            ]);

        $data = $response->json();

        if (!$response->successful() || !isset($data['id'])) {
            $errorMessage = $data['error']['message']
                ?? $data['error']['error_user_msg']
                ?? 'Failed to upload media';
            throw new Exception($errorMessage);
        }

        return $data;
    }

    function getSessionId($appId, array $fileData, $accessToken)
    {
        try {

            $url      = "https://graph.facebook.com/v23.0/{$appId}/uploads";
            $response = Http::post($url, [
                'file_name'    => $fileData['name'],
                'file_length'  => $fileData['size'],
                'file_type'    => $fileData['type'],
                'access_token' => $accessToken
            ]);
            $data = $response->json();
            if ($response->failed() || !is_array($data) || !isset($data['id'])) {
                throw new Exception(@$data['error']['message'] ?? "Couldn\'t upload your header image! Please try again later.");
            }
            return $data;
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage() ?? "Couldn\'t upload your header image! Please try again later.");
        }
    }

    function getMediaHandle($sessionId, $accessToken, $filePath, $mimeType)
    {
        try {

            $cleanSessionId = str_replace('upload:', '', $sessionId);
            $url            = "https://graph.facebook.com/v23.0/upload:$cleanSessionId";
            $fileContents   = file_get_contents($filePath);

            $response = Http::withHeaders([
                'Authorization' => "OAuth $accessToken",
                'file_offset'   => '0',
            ])->withBody($fileContents, $mimeType)
                ->post($url);

            $data = $response->json();
            if ($response->failed() || !is_array($data) || !isset($data['h'])) {
                throw new Exception(@$data['error']['error_user_msg'] ?? @$data['error']['message']);
            }
            return $data['h'];
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage() ?? "Failed to get the media handle! Please try again later.");
        }
    }

    public function getMediaUrl($mediaId, $accessToken)
    {
        $url = $this->getWhatsAppBaseUrl() . "{$mediaId}";

        $response = CurlRequest::curlContent($url, [
            "Authorization: Bearer {$accessToken}"
        ]);

        $data = json_decode($response, true);

        if (!is_array($data) || isset($data['error']) || !isset($data['url'])) {
            throw new Exception(@$data['error']['error_user_msg'] ?? @$data['error']['message'] ?? "Failed to load the media URL. Please try again later.");
        }

        return $data;
    }

    public function getWhatsAppBaseUrl()
    {
        return "https://graph.facebook.com/v22.0/";
    }

    public function storedMediaToLocal($mediaUrl, $mediaId, $accessToken, $userId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}",
            ])->get($mediaUrl);

            if ($response->failed()) {
                throw new Exception("Message sending fail for the download media");
            }

            $fileContent = $response->body();
            $mimeType    = $response->header('Content-Type');

            $fileExtension = explode('/', $mimeType)[1];
            $fileName      = "{$mediaId}.{$fileExtension}";

            $parentFolder = getFilePath('conversation');
            $subFolder    = "{$userId}/" . date('Y/m/d');
            $folderPath   = $parentFolder . "/" . $subFolder;
            $filePath     = $folderPath . "/" . $fileName;

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0755, true);
            }

            file_put_contents($filePath, $fileContent);

            return $subFolder . "/" . $fileName;
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage());
        }
    }

    public function  submitTemplate($businessAccountId, $accessToken, $templateData = [])
    {
        $header = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
        ];

        try {

            $response = CurlRequest::curlPostContent($this->getWhatsAppBaseUrl() . "{$businessAccountId}/message_templates", $templateData, $header);
            $data     = json_decode($response, true);

            if (!is_array($data) || isset($data['error'])) {
                throw new Exception(@$data['error']['error_user_msg'] ?? @$data['error']['message'] ?? "Something went wrong");
            }
            return $data;
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage() ?? "Something went wrong");
        }
    }

    public function sendAutoReply($user, $conversation, $message)
    {

        $contact  = $conversation->contact;
        $userAiSetting = $user->aiSetting;

        if (!$userAiSetting) return;

        if ($userAiSetting->status == Status::DISABLE || !$contact || $conversation->ai_reply == Status::NO) return;

        $provider = [
            'openai' => OpenAi::class,
            'gemini' => Gemini::class
        ];

        $activeProvider   = AiAssistant::active()->first();

        if (!$activeProvider) return;

        if ($user->ai_assistance == 0) return;

        $aiAssistantClass = $provider[$activeProvider->provider];

        $aiAssistant = new $aiAssistantClass();

        if ($userAiSetting->status == Status::ENABLE) {
            $systemPrompt    = $userAiSetting->system_prompt;
            $aiResponse      = $aiAssistant->getAiReply($systemPrompt, $message);
            $whatsappAccount = $user->currentWhatsapp();

            if ($aiResponse['success'] == true) {

                if ($aiResponse['response'] == null && $userAiSetting->fallback_response != null) {
                    $request = new Request([
                        'message' => $userAiSetting->fallback_response,
                    ]);
                    $conversation->ai_reply = Status::NO;
                    $conversation->save();
                } else {
                    $request = new Request([
                        'message' => $aiResponse['response'],
                    ]);
                }

                $messageSend = $this->messageSend($request, $contact->mobileNumber, $whatsappAccount);
                extract($messageSend);

                $message                      = new Message();
                $message->user_id             = $user->id;
                $message->whatsapp_account_id = $whatsappAccount->id;
                $message->whatsapp_message_id = $whatsAppMessage[0]['id'];
                $message->conversation_id     = $conversation->id;
                $message->type                = Status::MESSAGE_SENT;
                $message->message             = $request->message;
                $message->media_id            = $mediaId;
                $message->message_type        = getIntMessageType($messageType);;
                $message->media_caption       = $mediaCaption;
                $message->media_filename      = $mediaFileName;
                $message->media_url           = $mediaUrl;
                $message->media_path          = $mediaPath;
                $message->mime_type           = $mimeType;
                $message->media_type          = $mediaType;
                $message->status              = Status::MESSAGE_SENT;
                $message->ordering            = Carbon::now();
                $message->ai_reply            = Status::YES;
                $message->save();

                $conversation->last_message_at = Carbon::now();
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
            }
        }
    }


    public function userBlockAction($whatsappAccount, $contact, $action = 'block')
    {
        $phoneNumberId = $whatsappAccount->phone_number_id;
        $accessToken   = $whatsappAccount->access_token;
        $url = $this->getWhatsAppBaseUrl() . "{$phoneNumberId}/block_users";

        $data = [
            'messaging_product' => 'whatsapp',
            'block_users' => [
                [
                    'user' => $contact->mobileNumber,
                ]
            ]
        ];

        try {
            if ($action == 'unblock') {
                $message = "Failed to unblock the user! Please try again later.";
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$accessToken}",
                ])->delete($url, $data);
            } else {
                $message = "Failed to block the user! Please try again later.";
                $response = Http::withHeaders([
                    'Authorization' => "Bearer {$accessToken}",
                ])->post($url, $data);
            }

            $responseData = $response->json();

            if (!is_array($responseData) || $response->failed() || isset($responseData['error']) || !isset($responseData['block_users'])) {
                throw new Exception(
                    @$responseData['error']['error_user_msg']
                        ?? @$responseData['error']['message']
                        ?? $message
                );
            }

            return $responseData;
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage() ?? $message);
        }
    }

    public function sendButtonMessage($toNumber, $whatsappAccount, $button)
    {

        $phoneNumberId    = $whatsappAccount->phone_number_id;
        $accessToken      = $whatsappAccount->access_token;
        $url              = $this->getWhatsAppBaseUrl() . "{$phoneNumberId}/messages";

        $data = [
            'messaging_product' => 'whatsapp',
            'recipient_type'    => 'individual',
            'to'                => $toNumber,
            'type'              => 'interactive'
        ];

        $buttonData = json_decode($button->buttons_json, true);

        $interactive = [
            'type'  => 'button'
        ];

        $interactive['body'] = [
            'text' => $buttonData['body']
        ];

        $interactive['footer'] = [
            'text' => $buttonData['footer']
        ];


        foreach ($buttonData['buttons'] as $button) {
            $interactive['action']['buttons'][] = [
                'type' => 'reply',
                'reply' => [
                    'id' => $button['text'],
                    'title' => $button['text']
                ]
            ];
        }

        $data['interactive'] = $interactive;

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$accessToken}",
            ])->post($url, $data);

            $responseData = $response->json();

            if (!is_array($responseData) || $response->failed() || isset($responseData['error'])) {
                throw new Exception(
                    @$responseData['error']['error_user_msg']
                        ?? @$responseData['error']['message']
                        ?? "Failed to send the message! Please try again later."
                );
            }

            return [
                'whatsAppMessage' => $responseData['messages'],
                'ctaUrlId'        => 0,
                'mediaId'         => null,
                'mediaUrl'        => null,
                'mediaPath'       => null,
                'mediaCaption'    => null,
                'mediaFileName'   => null,
                'messageType'     => 'button',
                'mimeType'        => null,
                'mediaType'       => null
            ];
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage() ?? "Failed to send the message! Please try again later.");
        }
    }
}
