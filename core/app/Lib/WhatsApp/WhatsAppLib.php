<?php

namespace App\Lib\WhatsApp;

use App\Constants\Status;
use App\Events\ReceiveMessage;
use App\Lib\AiAssistantLib\Gemini;
use App\Lib\AiAssistantLib\OpenAi;
use App\Lib\CurlRequest;
use App\Models\AiAssistant;
use App\Models\Contact;
use App\Models\Message;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class WhatsAppLib
{
    public function sendInteractiveListMessage($toNumber, $whatsappAccount, $interactiveList)
    {
        $this->ensureMarketingMessageAllowed($toNumber, $whatsappAccount);

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
        $this->ensureMarketingMessageAllowed($toNumber, $whatsappAccount);

        $phoneNumberId = $whatsappAccount->phone_number_id;
        $accessToken   = $whatsappAccount->access_token;

        $url = $this->getWhatsAppBaseUrl() . "{$phoneNumberId}/messages";

        $data = [
            'messaging_product' => 'whatsapp',
            'recipient_type'    => 'individual',
            'to'                => $toNumber,
            'type'              => 'interactive'
        ];

        if ($ctaUrl) {
            $interactive = [
                'type'   => 'cta_url',
                'header' => $ctaUrl->header,
                'body'   => $ctaUrl->body,
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
        if (
            strtoupper($template->category?->name ?? '') === 'MARKETING'
            && $contact->is_marketing_opted_out == Status::YES
        ) {
            throw new Exception('Promotional message not sent because the contact opted out of marketing messages.');
        }

        $phoneNumberId = $whatsappAccount->phone_number_id;
        $accessToken   = $whatsappAccount->access_token;

        $bodyParams = [];
        $headerParams = [];
        $buttonParams = [];

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

        // keyed by the button position inside the template's button list
        foreach (($request->button_variables ?? []) as $buttonIndex => $value) {
            $buttonParams[$buttonIndex] = [
                'type' => 'text',
                'text' => $value ?? '',
            ];
        }

        $templateHeaderParams = parseTemplateParams($headerParams, $contact);
        $templateBodyParams   = parseTemplateParams($bodyParams, $contact);
        $templateButtonParams = parseTemplateParams($buttonParams, $contact);

        $components = $this->buildTemplateComponents($template, $templateHeaderParams, $templateBodyParams, $templateButtonParams);

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

    /**
     * Build the `components` array of a WhatsApp Cloud API template message.
     *
     * Shared by the inbox, the external API, flow nodes and the campaign cron so
     * every send path produces the same payload.
     *
     * @param  array $headerParams  parsed params, e.g. [['type' => 'text', 'text' => 'John']]
     * @param  array $bodyParams    parsed params, same shape as $headerParams
     * @param  array $buttonParams  parsed params keyed by button index, e.g. [0 => ['type' => 'text', 'text' => 'ORD-1']]
     */
    public function buildTemplateComponents($template, array $headerParams = [], array $bodyParams = [], array $buttonParams = [])
    {
        $components = [];
        $isAuthentication = strtoupper($template->category?->name ?? '') === 'AUTHENTICATION';

        // authentication templates cannot carry a header component
        if (count($template->cards) == 0 && !$isAuthentication) {
            if (count($headerParams)) {
                $components[] = [
                    'type' => 'header',
                    'parameters' => array_values($headerParams)
                ];
            } elseif ($template->header_format === 'IMAGE' && !empty($template->header_media)) {
                $components[] = [
                    'type' => 'header',
                    'parameters' => [
                        [
                            'type' => 'image',
                            'image' => [
                                'link' => url(getFilePath('templateHeader') . '/' . $template->header_media)
                            ]
                        ]
                    ]
                ];
            }
        }

        $components[] = [
            'type' => 'body',
            'parameters' => array_values($bodyParams)
        ];

        $components = array_merge($components, $this->templateButtonComponents($template, $bodyParams, $buttonParams));

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
                } elseif ($card->header_format == 'VIDEO') {
                    $cardData['components'][] = [
                        'type' => 'header',
                        'parameters' => [
                            [
                                'type' => 'video',
                                'video' => [
                                    'id' => $card->media_id
                                ]
                            ]
                        ]
                    ];
                }

                $cardData['components'] = array_merge($cardData['components'], $this->cardButtonComponents($card));

                $cards[] = $cardData;
            }

            $components[] = [
                'type' => 'carousel',
                'cards' => $cards
            ];
        }

        return $components;
    }

    /**
     * Button components of a non-carousel template.
     *
     * Only buttons that need a runtime value produce a component; `index` is the
     * button's zero based position inside the template's button list, so buttons
     * that need nothing (quick reply, phone number, static url) still take a slot.
     */
    private function templateButtonComponents($template, array $bodyParams, array $buttonParams)
    {
        // Meta requires every authentication template to be sent with its OTP button
        // expressed as a url button holding the same code as the body parameter,
        // no matter whether the button is COPY_CODE, ONE_TAP or ZERO_TAP.
        if (strtoupper($template->category?->name ?? '') === 'AUTHENTICATION') {
            $firstBodyParam = count($bodyParams) ? reset($bodyParams) : [];
            $otp            = $firstBodyParam['text'] ?? '';

            if ($otp === '' || is_null($otp)) {
                throw new Exception('An OTP code is required to send this authentication template.');
            }

            return [
                [
                    'type' => 'button',
                    'sub_type' => 'url',
                    'index' => '0',
                    'parameters' => [
                        [
                            'type' => 'text',
                            'text' => $otp
                        ]
                    ]
                ]
            ];
        }

        $buttonComponents = [];

        foreach ((is_array($template->buttons) ? $template->buttons : []) as $index => $button) {
            $type  = strtoupper($button['type'] ?? '');
            $value = $buttonParams[$index]['text'] ?? null;

            if ($value === null || $value === '') {
                continue;
            }

            if ($type == 'URL' && $this->hasDynamicVariable($button['url'] ?? '')) {
                $buttonComponents[] = [
                    'type' => 'button',
                    'sub_type' => 'url',
                    'index' => (string) $index,
                    'parameters' => [
                        [
                            'type' => 'text',
                            'text' => $value
                        ]
                    ]
                ];
            } elseif ($type == 'COPY_CODE') {
                $buttonComponents[] = [
                    'type' => 'button',
                    'sub_type' => 'copy_code',
                    'index' => (string) $index,
                    'parameters' => [
                        [
                            'type' => 'coupon_code',
                            'coupon_code' => $value
                        ]
                    ]
                ];
            }
        }

        return $buttonComponents;
    }

    /**
     * Button components of a single carousel card. `index` is the button position
     * inside that card, and a dynamic url button must carry its suffix parameter.
     */
    private function cardButtonComponents($card)
    {
        $cardButtons = [];

        foreach (($card->buttons['buttons'] ?? []) as $btnIndex => $button) {
            if (strtoupper($button['type'] ?? '') != 'URL' || !$this->hasDynamicVariable($button['url'] ?? '')) {
                continue;
            }

            // there is no per card button input yet, so fall back to the suffix Meta
            // stored as the button example when the template was created or imported
            $suffix = $this->dynamicUrlSuffix($button['url'] ?? '', $button['example'][0] ?? null);

            if ($suffix === null || $suffix === '') {
                continue;
            }

            $cardButtons[] = [
                'type' => 'button',
                'sub_type' => 'url',
                'index' => (string) $btnIndex,
                'parameters' => [
                    [
                        'type' => 'text',
                        'text' => $suffix
                    ]
                ]
            ];
        }

        return $cardButtons;
    }

    private function hasDynamicVariable($value)
    {
        return (bool) preg_match('/\{\{\s*\d+\s*\}\}/', (string) $value);
    }

    /**
     * A dynamic url button is sent with only the part that replaces {{1}}, while Meta
     * stores the example as a whole url, so strip the static prefix off the example.
     */
    private function dynamicUrlSuffix($url, $example)
    {
        if ($example === null || $example === '') {
            return null;
        }

        $prefix = explode('{{', (string) $url)[0];

        if ($prefix !== '' && str_starts_with($example, $prefix)) {
            return substr($example, strlen($prefix));
        }

        return $example;
    }

    private function ensureMarketingMessageAllowed($toNumber, $whatsappAccount): void
    {
        $normalizedNumber = preg_replace('/\D/', '', $toNumber);

        $isOptedOut = Contact::where('user_id', $whatsappAccount->user_id)
            ->whereRaw('CONCAT(mobile_code, mobile) = ?', [$normalizedNumber])
            ->where('is_marketing_opted_out', Status::YES)
            ->exists();

        if ($isOptedOut) {
            throw new Exception('Promotional message not sent because the contact opted out of marketing messages.');
        }
    }

    public function messageSend($request, $toNumber, $whatsappAccount,$replyToMessage = null)
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
            if ($replyToMessage !== null && $replyToMessage->whatsapp_message_id) {
                $data['context'] = ['message_id' => $replyToMessage->whatsapp_message_id];
            }
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
            if ($replyToMessage !== null && $replyToMessage->whatsapp_message_id) {
                $data['context'] = ['message_id' => $replyToMessage->whatsapp_message_id];
            }
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
            if ($replyToMessage !== null && $replyToMessage->whatsapp_message_id) {
                $data['context'] = ['message_id' => $replyToMessage->whatsapp_message_id];
            }
            $data['video'] = [
                'id'      => $mediaId,
                'caption' => $mediaCaption
            ];
            $mediaType     = 'video';
            $mimeType      = mime_content_type($file->getPathname());
        } else if ($request->hasFile('audio') || $request->get('audio') instanceof UploadedFile) {
            $file          = $request->file('audio') ?: $request->get('audio');
            $mediaUpload   = $this->uploadMedia($mediaLink, $file, $accessToken);
            $mediaId       = $mediaUpload['id'];
            $mediaCaption  = $request->message;
            $data['type']  = 'audio';
            if ($replyToMessage !== null && $replyToMessage->whatsapp_message_id) {
                $data['context'] = ['message_id' => $replyToMessage->whatsapp_message_id];
            }
            $data['audio'] = [
                'id'      => $mediaId
            ];
            $mediaType     = 'audio';
            $mimeType      = mime_content_type($file->getPathname());
        } else if ($request->latitude && $request->longitude) {
            $data['type'] = 'location';
            if ($replyToMessage !== null && $replyToMessage->whatsapp_message_id) {
                $data['context'] = ['message_id' => $replyToMessage->whatsapp_message_id];
            }
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
            if ($replyToMessage !== null && $replyToMessage->whatsapp_message_id) {
                $data['context'] = ['message_id' => $replyToMessage->whatsapp_message_id];
            }
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
                'location'        => $data['location'] ?? null,
                'replyToMessage'  => $replyToMessage ?? null,
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
        } elseif ($file instanceof UploadedFile) {
            $filePath = $file->getPathname();
            $fileName = basename($filePath);
        } elseif (is_string($file) && file_exists($file)) {
            $filePath = $file;
            $fileName = basename($file);
        } else {
            throw new Exception('Invalid file provided for upload');
        }

        $response = Http::withToken($accessToken)
            ->attach(
                'file',
                file_get_contents($filePath),
                $fileName
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

            $parentFolder = 'conversation';
            $subFolder    = "{$userId}/" . date('Y/m/d');
            $folderPath   = $parentFolder . "/" . $subFolder;
            $filePath     = $folderPath . "/" . $fileName;

            if (s3_configured()) {
                s3_disk()->put($filePath, $fileContent, 'public');
            } else {
                $localFolder = getFilePath('conversation') . "/" . $subFolder;
                if (!file_exists($localFolder)) {
                    mkdir($localFolder, 0755, true);
                }
                file_put_contents($localFolder . "/" . $fileName, $fileContent);
            }

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

                // The reply has already been sent and saved; a failing realtime push must
                // not turn the webhook into a 500.
                try {
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
                        'mediaPath'       => s3_configured() ? url('api/inbox/media/by-path/') : getFilePath('conversation')
                    ]));
                } catch (\Throwable $ex) {
                    Log::error('AI auto reply realtime update failed for message ' . $message->id . ': ' . $ex->getMessage());
                }
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

        // The flow builder json_encode()s this payload into a column that is itself cast
        // to array, so the accessor returns a string on those rows and an array on others.
        $buttonData = $button->buttons_json;

        if (is_string($buttonData)) {
            $buttonData = json_decode($buttonData, true);
        }

        if (!is_array($buttonData) || empty($buttonData['buttons'])) {
            throw new Exception("The button message has no buttons to send.");
        }

        $interactive = [
            'type'  => 'button'
        ];

        $interactive['body'] = [
            'text' => $buttonData['body'] ?? ''
        ];

        // Meta rejects a footer whose text is empty, so only send one when there is any.
        if (strlen(trim($buttonData['footer'] ?? ''))) {
            $interactive['footer'] = [
                'text' => $buttonData['footer']
            ];
        }

        foreach ($buttonData['buttons'] as $index => $replyButton) {
            $interactive['action']['buttons'][] = [
                'type' => 'reply',
                'reply' => [
                    // Positional, not the label: two buttons may legitimately share a
                    // label and the reply has to tell us which branch to follow.
                    'id' => 'btn_' . $index,
                    'title' => $replyButton['text']
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
