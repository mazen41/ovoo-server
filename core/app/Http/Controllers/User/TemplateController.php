<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use Carbon\Carbon;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Traits\WhatsappManager;
use Illuminate\Validation\Rule;
use App\Models\TemplateCategory;
use App\Models\TemplateLanguage;
use App\Http\Controllers\Controller;
use App\Lib\CurlRequest;
use App\Lib\WhatsApp\WhatsAppLib;
use App\Models\TemplateCard;
use App\Models\WhatsappAccount;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class TemplateController extends Controller
{
    use WhatsappManager;

    public function index()
    {
        $pageTitle          = "Manage Template";
        $user               = getParentUser();
        $templateCategories = TemplateCategory::get();
        $templates          = Template::where('user_id', getParentUser()->id)
            ->where('whatsapp_account_id', getWhatsappAccountId($user))
            ->searchable(['name', 'whatsapp_template_id'])
            ->filter(['status', 'category_id'])
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());

        $whatsappAccounts = WhatsappAccount::where('user_id', $user->id)->get();
        return view('Template::user.template.index', compact('pageTitle','user','templates', 'templateCategories', 'whatsappAccounts'));
    }

    public function createTemplate()
    {
        $user      = getParentUser();
        $templates = $user->templates()->where('created_at', '>=', Carbon::now()->subHour())->count();

        if ($templates >= 100) {
            $notify[] = ['error', 'You can create only 100 templates per hour.'];
            return back()->withNotify($notify);
        }

        $pageTitle          = "Create Template";
        $templateCategories = TemplateCategory::get();
        $templateLanguages  = TemplateLanguage::get();
        $preMadeTemplates   = json_decode(file_get_contents(resource_path("views/" . str_replace(".", "/", activeTemplate()) . "user/template/pre_made_template.json")));

        return view('Template::user.template.create', compact('pageTitle', 'templateCategories', 'templateLanguages', 'preMadeTemplates'));
    }

    public function createCarouselTemplate()
    {
        $user      = getParentUser();
        $templates = $user->templates()->where('created_at', '>=', Carbon::now()->subHour())->count();

        if ($templates >= 100) {
            $notify[] = ['error', 'You can create only 100 templates per hour.'];
            return back()->withNotify($notify);
        }

        $pageTitle          = "Create Carousel Template";
        $templateCategories = TemplateCategory::get();
        $templateLanguages  = TemplateLanguage::get();

        return view('Template::user.template.create_carousel', compact('pageTitle', 'templateCategories', 'templateLanguages'));
    }

    public function storeCarouselTemplate(Request $request)
    {
        $request->validate([
            'name'                          => 'required|string|max:512|regex:/^[a-zA-Z0-9_-]+$/',
            'language_id'                   => 'required',
            'template_body'                 => 'required|string|max:1024',

            'cards'                         => 'required|array|min:2|max:10',
            'cards.*.body'                  => 'nullable|string|max:160',
            'cards.*.header_format'         => 'required|in:IMAGE,VIDEO',
            'cards.*.header.handle'         => 'required|file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/webp,video/mp4,video/quicktime,video/x-msvideo',

            'cards.*.buttons'                => 'required|array|min:1|max:2',
            'cards.*.buttons.*.text'         => 'required|string|max:25',
            'cards.*.buttons.*.type'         => 'required|string|in:QUICK_REPLY,URL,PHONE_NUMBER',
            'cards.*.buttons.*.url'          => 'required_if:cards.*.buttons.*.type,URL|url|max:2048',
            'cards.*.buttons.*.phone_number' => 'required_if:cards.*.buttons.*.type,PHONE_NUMBER|numeric',
        ]);

        $user = getParentUser();

        if (!featureAccessLimitCheck($user->template_limit)) {
            $notify = 'Your template limit is over. Please upgrade your plan';
            return responseManager('limit', $notify);
        }

        $whatsappAccount = WhatsappAccount::where('user_id', $user->id)->where('id', $request->whatsapp_account_id)->first();

        if (!$whatsappAccount) {
            return responseManager('invalid', 'The selected whatsapp account is invalid');
        }

        $templateExists = Template::where('user_id', $user->id)
            ->where('name', $request->name)
            ->where('language_id', $request->language_id)
            ->exists();

        if ($templateExists) {
            $notify = 'Sorry! The template name already exists';
            return responseManager('exists', $notify);
        }

        $language = TemplateLanguage::find($request->language_id);

        if (!$language) {
            $notify = 'The template language is not found';
            return responseManager('not_found', $notify);
        }

        $whatsappManager = new WhatsAppLib();

        $whatsappTemplateData = [
            'name'     => $request->name,
            'language' => $language->code,
            'category' => "MARKETING"
        ];

        $whatsappTemplateData['components'] = [];

        if (!empty($request->template_body)) {
            $whatsappTemplateData['components'][] = $this->templateBody($request, "MARKETING");
        }

        $cardsPayload = [];
        $dbCards = [];

        $mediaLink = $whatsappManager->getWhatsAppBaseUrl() . "{$whatsappAccount->phone_number_id}/media";

        foreach ($request->cards as $index => $card) {
            $headerFormat    = $card['header_format'];
            $headerMediaName = null;
            $header          = $card['header'] ?? [];
            $cardBody        = $card['body'] ?? '';
            $cardData['components'] = [];

            try {

                if (isset($header['handle']) && !empty($header['handle'])) {

                    $uploadFile = $request->file('cards.' . $index . '.header.handle');

                    if (!$uploadFile instanceof \Illuminate\Http\UploadedFile) {
                        return responseManager('error', 'Header handle file missing or invalid');
                    }

                    $headerMediaName = fileUploader($uploadFile, getFilePath('templateCardHeader'), getFileSize('templateCardHeader'));
                }

                if (!$headerMediaName) {
                    return responseManager('error', 'Could not upload header media');
                }

                $filePath = getFilePath('templateCardHeader') . '/' . $headerMediaName;

                $fileData = [];
                $fileData['name'] = $headerMediaName;
                $fileData['path'] = $filePath;
                $fileData['size'] = filesize($filePath);
                $fileData['type'] = mime_content_type($filePath);

                $sessionId        = $whatsappManager->getSessionId($whatsappAccount->meta_app_id, $fileData, $whatsappAccount->access_token);
                $mediaId          = $whatsappManager->uploadMedia($mediaLink, $filePath, $whatsappAccount->access_token);
                $header['handle'] = $whatsappManager->getMediaHandle($sessionId['id'], $whatsappAccount->access_token, $fileData['path'], $fileData['type']);
            } catch (\Exception $e) {
                return responseManager('error', $e->getMessage());
            }

            $cardData['components'][] = $this->templateHeader($headerFormat, $header);

            if (isset($card['body']) && !empty($card['body'])) {
                $cardData['components'][] = [
                    'type' => "BODY",
                    'text' => $card['body']
                ];
            }

            $cardButtons = $this->templateButtons($card['buttons'], "MARKETING", true);
            if (!empty($cardButtons)) {
                $cardData['components'][]  = [
                    'type' => "BUTTONS",
                    'buttons' => $cardButtons
                ];
            }

            $cardsPayload[] = $cardData;

            $headerComponent = $cardData['components'][0];
            $bodyComponent   = !empty($cardBody) ? $cardBody : null;
            $buttonComponent = end($cardData['components']);

            $dbCards[] = [
                'header'     => $headerComponent,
                'body'       => $bodyComponent,
                'buttons'    => $buttonComponent,
                'media_id'   => $mediaId['id'],
                'media_path' => $headerMediaName,
                'type'       => $headerFormat
            ];
        }

        $carouselParam = [
            'type' => "carousel",
            'cards' => $cardsPayload
        ];

        $whatsappTemplateData['components'][] = $carouselParam;

        try {
            $whatsappData = $whatsappManager->submitTemplate($whatsappAccount->whatsapp_business_account_id, $whatsappAccount->access_token, $whatsappTemplateData);
        } catch (\Exception $e) {
            return responseManager('error', $e->getMessage());
        }

        $category = TemplateCategory::where('name', 'MARKETING')->first();

        $template                              = new Template();
        $template->user_id                     = $user->id;
        $template->whatsapp_account_id         = $whatsappAccount->id;
        $template->whatsapp_template_id        = $whatsappData['id'];
        $template->name                        = $request->name;
        $template->category_id                 = $category->id;
        $template->language_id                 = $request->language_id;
        $template->body                        = $request->template_body;
        $template->status                      = metaTemplateStatus($whatsappData['status']);
        $template->save();

        foreach ($dbCards as $card) {
            $templateCard                = new TemplateCard();
            $templateCard->template_id   = $template->id;
            $templateCard->user_id       = $user->id;
            $templateCard->media_id      = $card['media_id'];
            $templateCard->header_format = $card['type'];
            $templateCard->media_path    = $card['media_path'];
            $templateCard->header        = $card['header'];
            $templateCard->body          = $card['body'];
            $templateCard->buttons       = $card['buttons'];
            $templateCard->save();
        }

        decrementFeature($user, 'template_limit');

        $notify = "Your carousel template submitted for approval";
        return responseManager('success', $notify, 'success');
    }

    public function storeTemplate(Request $request)
    {
        $this->validation($request);

        $user = getParentUser();

        if (!featureAccessLimitCheck($user->template_limit)) {
            $notify = 'Your template limit is over. Please upgrade your plan';
            return responseManager('limit', $notify);
        }

        $whatsappAccount = WhatsappAccount::where('user_id', $user->id)->where('id', $request->whatsapp_account_id)->first();

        if (!$whatsappAccount) {
            return responseManager('invalid', 'The selected whatsapp account is invalid');
        }

        $templateExists = Template::where('user_id', $user->id)
            ->where('name', $request->name)
            ->where('language_id', $request->language_id)
            ->exists();

        if ($templateExists) {
            $notify = 'Sorry! The template name already exists';
            return responseManager('exists', $notify);
        }

        $category     = TemplateCategory::find($request->category_id);

        if (!$category) {
            $notify = 'The template category is not found';
            return responseManager('not_found', $notify);
        }

        $language = TemplateLanguage::find($request->language_id);

        if (!$language) {
            $notify = 'The template language is not found';
            return responseManager('not_found', $notify);
        }

        $header          = $request->header ?? [];
        $headerFormat    = $request->header_format ?? null;
        $headerMediaName = null;

        $whatsappManager = new WhatsAppLib();

        try {
            if ($request->hasFile('header.handle')) {

                $headerMediaName = fileUploader($header['handle'], getFilePath('templateHeader'), getFileSize('templateHeader'));

                if (!$headerMediaName) {
                    return responseManager('error', 'Couldn\'t upload your file');
                }

                $fileData         = [];
                $fileData['name'] = $headerMediaName;
                $fileData['path'] = getFilePath('templateHeader') . '/' . $headerMediaName;
                $fileData['size'] = filesize($fileData['path']);
                $fileData['type'] = mime_content_type($fileData['path']);

                $sessionId        = $whatsappManager->getSessionId($whatsappAccount->meta_app_id, $fileData, $whatsappAccount->access_token);
                $header['handle'] = $whatsappManager->getMediaHandle($sessionId['id'], $whatsappAccount->access_token, $fileData['path'], $fileData['type']);
            }
        } catch (\Exception $e) {
            return responseManager('error', $e->getMessage());
        }

        $whatsappTemplateData = [
            'name'     => $request->name,
            'language' => $language->code,
            'category' => $category->name,
        ];

        $whatsappTemplateData['components'] = [];

        if (!empty($headerFormat)) {
            if ($category->name !== 'AUTHENTICATION') {
                $whatsappTemplateData['components'][] = $this->templateHeader($headerFormat, $header);
            }
        }
        if (!empty($request->template_body)) {
            $whatsappTemplateData['components'][] = $this->templateBody($request, $category->name);
        }

        if (!empty($request->footer)) {
            $whatsappTemplateData['components'][] = $this->templateFooter($request, $category->name);
        }

        $templateButtons = $this->templateButtons($request, $category->name);

        if (!empty($templateButtons)) {
            $whatsappTemplateData['components'][] = [
                "type" => 'BUTTONS',
                "buttons" => $templateButtons,
            ];
        }

        try {
            $whatsappData = $whatsappManager->submitTemplate($whatsappAccount->whatsapp_business_account_id, $whatsappAccount->access_token, $whatsappTemplateData);
        } catch (\Exception $e) {
            return responseManager('error', $e->getMessage());
        }

        $template                              = new Template();
        $template->user_id                     = $user->id;
        $template->whatsapp_account_id         = $whatsappAccount->id;
        $template->whatsapp_template_id        = $whatsappData['id'];
        $template->name                        = $request->name;
        $template->category_id                 = $request->category_id;
        $template->language_id                 = $request->language_id;
        $template->body                        = $request->template_body;
        $template->header                      = $request->header_format ? $header : null;
        $template->header_format               = $headerFormat ?? null;
        $template->header_media                = $headerMediaName ?? null;
        $template->footer                      = $request->footer ?? null;
        $template->status                      = metaTemplateStatus($whatsappData['status']);
        $template->buttons                     = count($templateButtons) ? $templateButtons : [];
        $template->code_expiration_minutes     = $request->code_expiration_minutes ?? null;
        $template->add_security_recommendation = $request->add_security_recommendation ? Status::YES : Status::NO;
        $template->save();

        decrementFeature($user, 'template_limit');

        $notify =  'Your template has been submitted for approval';
        return responseManager('template_created', $notify, 'success');
    }

    public function validation($request)
    {
        $request->validate([
            'name'                => 'required|string|max:512|regex:/^[a-zA-Z0-9_-]+$/',
            'category_id'         => 'required|exists:template_categories,id',
            'language_id'         => 'required|exists:template_languages,id',
            'header_format'       => ['nullable', Rule::in(templateHeaderTypes())],
            'template_body'       => 'required|string|max:1024',
            'footer'              => 'nullable|string|max:60',
            'whatsapp_account_id' => 'required',
            'buttons'             => 'nullable|array',
            'buttons.*.text'      => 'required|string',
        ], [
            'name.regex' => 'The template name must only contain letters, numbers, underscores, and hyphens',
        ]);
    }

    private function templateButtons($request, $category, $card = false)
    {
        $formateButtons = [];

        $allButtons = [];

        if ($card) {
            $allButtons = $request;
        } else {
            $allButtons = $request->buttons;
        }

        foreach ($allButtons ?? [] as $button) {

            $buttonType = $button['type'];

            if ($category == 'AUTHENTICATION' && $buttonType !== 'OTP') {
                continue;
            }

            $requiredFields = [
                'QUICK_REPLY'  => 'quick_reply',
                'PHONE_NUMBER' => 'phone_number',
                'URL'          => 'url',
                'OTP'          => 'otp_type',
            ];

            if ($buttonType == 'OTP') {
                $formateButtons[] = [
                    'type' => 'OTP',
                    'otp_type' => $button['otp_type'],
                ];
            } elseif (in_array($buttonType, ['PHONE_NUMBER', 'URL'])) {
                $field = $requiredFields[$buttonType];
                $formateButtons[] = [
                    'type' => $buttonType,
                    'text' => $button['text'],
                    $field => $button[$field],
                ];
            } else {
                $formateButtons[] = [
                    'type' => $buttonType,
                    'text' => $button['text'],
                ];
            }
        }

        return $formateButtons;
    }


    private function templateHeader($headerFormat, $header)
    {
        $templateHeader = [
            "type"   => 'HEADER',
            "format" => $headerFormat,
        ];

        if ($headerFormat === 'TEXT' && !empty($header['text'])) {
            $templateHeader['text'] = $header['text'];
            if (preg_match('/\{\{\d+\}\}/', $header['text']) && !empty($header['example'])) {
                $templateHeader['example'] = $header['example'];
            }
        } elseif (in_array($headerFormat, ['IMAGE', 'VIDEO', 'DOCUMENT']) && !empty($header['handle'])) {
            $templateHeader['example'] = [
                "header_handle" => $header['handle'],
            ];
        }

        return $templateHeader;
    }

    private function templateBody($request, $category)
    {
        $bodyText = $request->template_body ?? '';

        $result = [
            "type" => 'BODY',
        ];

        if ($category && $category === 'AUTHENTICATION' && $request->add_security_recommendation) {
            $result['add_security_recommendation'] = true;
        } else {
            $result['text'] = $bodyText;

            if ($bodyText && isset($request->body['example']['body_text'])) {
                $bodyExamples = $request->body['example']['body_text'];

                if (!empty($bodyExamples) && is_array($bodyExamples)) {
                    if (!isset($bodyExamples[0]) || !is_array($bodyExamples[0])) {
                        $bodyExamples = [array_values($bodyExamples)];
                    }
                }

                $result['example'] = [
                    'body_text' => $bodyExamples
                ];
            }
        }

        return $result;
    }




    public function templateFooter($request, $category)
    {
        if ($category === 'AUTHENTICATION' && $request->code_expiration_minutes) {
            return [
                "type" => "FOOTER",
                "code_expiration_minutes" => (int) $request->code_expiration_minutes,
            ];
        }

        if (!empty($request->footer)) {
            return [
                "type" => "FOOTER",
                "text" => $request->footer,
            ];
        }

        return null;
    }

    public function getTemplates(Request $request)
    {
        $user = getParentUser();

        if (request()->account_id) {
            $whatsappAccount = WhatsappAccount::where('user_id', $user->id)->find(request()->account_id);
        } else {
            $whatsappAccount = WhatsappAccount::where('user_id', $user->id)->where('is_default', Status::YES)->first();
        }


        if (!$whatsappAccount) {
            return apiResponse('invalid', 'error', ['The selected whatsapp account is invalid']);
        }

        $templates = Template::where('user_id', $user->id)
            ->where('whatsapp_account_id', $whatsappAccount->id)
            ->approved()
            ->with('cards')
            ->get() ?? [];

        return apiResponse(
            'templates',
            'success',
            ['Template list'],
            ['templates' => $templates]
        );
    }


    public function deleteTemplate($templateId)
    {
        $template = Template::where('user_id', getParentUser()->id)->whereHas('whatsappAccount')->where('id', $templateId)->firstOrFail();

        if ($template->campaigns()->count()) {
            return responseManager('error', 'Unable to delete template,This template is used in campaign');
        }

        if ($template->messages()->count()) {
            return responseManager('error', 'Unable to delete template,This template is used in message');
        }

        $businessAccountId = $template->whatsappAccount->whatsapp_business_account_id;
        $accessToken       = $template->whatsappAccount->access_token;

        try {
            $header = [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken
            ];

            $response = CurlRequest::curlDeleteContent("https://graph.facebook.com/v22.0/{$businessAccountId}/message_templates?name={$template->name}", null, $header);
            $data = json_decode($response, true);

            if (!is_array($data) || isset($data['error'])) {
                $notify[] = ['error', @$data['error']['error_user_msg'] ?? "Something went to wrong"];
                return back()->withNotify($notify);
            }

            if ($template->header_media) {
                $headerMedia = getFilePath('templateHeader') . '/' . $template->header_media;
                if ($headerMedia && File::exists($headerMedia)) {
                    File::delete($headerMedia);
                }
            }
            $template->delete();

            $notify[] = ['success', 'Template deleted successfully'];
            return back()->withNotify($notify);
        } catch (Exception $ex) {
            $notify[] = ['error', $ex->getMessage() ?? "Something went to wrong"];
            return back()->withNotify($notify);
        }
    }

    public function checkTemplateStatus($templateId)
    {
        $template = Template::where('user_id', getParentUser()->id)->where('id', $templateId)->whereHas('whatsappAccount')->firstOrFail();
        $account  = $template->whatsappAccount;

        $businessAccountId = $account->whatsapp_business_account_id;
        $accessToken       = $account->access_token;

        try {
            $header = [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $accessToken
            ];

            $response = CurlRequest::curlContent("https://graph.facebook.com/v22.0/{$businessAccountId}/message_templates?name={$template->name}", $header);
            $data     = json_decode($response, true);
            if (!is_array($data) || isset($data['error']) || !isset($data['data'][0]['status'])) {
                $notify[] = ['error', @$data['error']['error_user_msg'] ?? @$data['error']['message'] ?? "Something went to wrong"];
                return back()->withNotify($notify);
            }

            $template->status = metaTemplateStatus($data['data'][0]['status']);
            $template->save();

            $notify[] = ['success', 'Template status has been updated'];
            return back()->withNotify($notify);
        } catch (Exception $ex) {
            $notify[] = ['error', $ex->getMessage() ?? "Something went to wrong"];
            return back()->withNotify($notify);
        }
    }

    public function fetchTemplates(Request $request)
    {
        $request->validate([
            'whatsapp_account' => 'required|integer',
        ]);

        $user            = getParentUser();
        $whatsappAccount = WhatsappAccount::where('user_id', $user->id)->where('id', $request->whatsapp_account)->first();

        if (!$whatsappAccount) {
            return responseManager('invalid', 'The selected whatsapp account is invalid');
        }

        if (!featureAccessLimitCheck($user->template_limit)) {
            $notify = 'Your template limit is over. Please upgrade your plan';
            return responseManager('limit', $notify);
        }

        $businessAccountId = $whatsappAccount->whatsapp_business_account_id;
        $accessToken       = $whatsappAccount->access_token;

        try {
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $accessToken,
            ];

            $response = Http::withHeaders($headers)->get("https://graph.facebook.com/v22.0/{$businessAccountId}/message_templates");
            $data     = $response->json();

            if (!$response->successful()) {
                $message = isset($data['error']) &&  isset($data['error']['message']) && is_string($data['error']['message']) ? $data['error']['message'] : "Something went to wrong";
                return responseManager('error', $message);
            }

            if (!isset($data['data']) || !is_array($data['data']) || count($data['data']) === 0) {
                return responseManager('error', 'No templates found for the selected whatsapp account');
            }

            $templates = $data['data'];

            $totalImported   = 0;
            $whatsappManager = new WhatsAppLib();
            $mediaLink       = $whatsappManager->getWhatsAppBaseUrl() . "{$whatsappAccount->phone_number_id}/media";

            foreach ($templates as $template) {
                $existingTemplate = Template::where('user_id', $user->id)
                    ->where('whatsapp_template_id', $template['id'])
                    ->first();

                $isCarousel = collect($template['components'] ?? [])->contains(function ($component) {
                    return strtoupper($component['type'] ?? '') == 'CAROUSEL';
                });

                if ($existingTemplate && (!$isCarousel || $existingTemplate->cards()->exists())) continue;

                if (!isset($template['components']) || !is_array($template['components']) || count($template['components']) === 0) {
                    continue;
                }

                $category = TemplateCategory::where("name", $template['category'])->first();

                if (!$category) {
                    $category       = new TemplateCategory();
                    $category->name = $template['category'];
                    $category->save();
                }

                $language = TemplateLanguage::where('code', $template['language'])->first();

                if (!$language) {
                    $language       = new TemplateLanguage();
                    $language->code = $template['language'];
                    $language->save();
                }

                $components = $template['components'];

                $headerMedia   = null;
                $headerFormat  = null;
                $footer        = null;
                $header        = [];
                $buttons       = [];
                $body          = null;
                $carouselCards = [];

                foreach ($components as $component) {
                    $type = strtoupper($component['type']);

                    //work with header
                    if ($type == 'HEADER') {
                        $headerFormat = strtoupper($component['format']);

                        if ($headerFormat == "IMAGE") {
                            $headerMedia  = $component['example']['header_handle'][0] ?? null;
                            $header['handle'] = $headerMedia;


                            if (!is_null($headerMedia)) {
                                $imageUrl  = $headerMedia;
                                $imageName = explode('/', strtok($imageUrl, '?'));
                                $imageName = end($imageName);

                                $filePath = getFilePath('templateHeader') . '/' . $imageName;

                                $imageData = file_get_contents($imageUrl);
                                file_put_contents($filePath, $imageData);

                                $headerMedia = $imageName;
                            }
                        }

                        if ($headerFormat == "TEXT") {
                            $header['text'] = $component['text'] ?? null;
                        }

                        continue;
                    }

                    //body
                    if ($type == 'BODY') {
                        $body = $component['text'] ?? null;
                        continue;
                    }

                    //footer
                    if ($type == 'FOOTER') {
                        $footer = $component['text'] ?? null;
                        continue;
                    }

                    //buttons
                    if ($type == 'BUTTONS') {
                        $buttons    = $component['buttons'] ?? [];
                        continue;
                    }

                    //carousel cards
                    if ($type == 'CAROUSEL') {
                        foreach (($component['cards'] ?? []) as $card) {
                            $cardHeader  = null;
                            $cardBody    = null;
                            $cardButtons = null;

                            foreach (($card['components'] ?? []) as $cardComponent) {
                                $cardComponentType = strtoupper($cardComponent['type'] ?? '');

                                if ($cardComponentType == 'HEADER') {
                                    $cardHeader = $cardComponent;
                                } elseif ($cardComponentType == 'BODY') {
                                    $cardBody = $cardComponent['text'] ?? null;
                                } elseif ($cardComponentType == 'BUTTONS') {
                                    $cardButtons = $cardComponent;
                                }
                            }

                            if (!$cardHeader) {
                                continue;
                            }

                            $cardMediaId   = null;
                            $cardMediaPath = null;
                            $headerHandle  = $cardHeader['example']['header_handle'][0] ?? null;

                            if ($headerHandle) {
                                $mediaResponse = Http::get($headerHandle);

                                if (!$mediaResponse->successful()) {
                                    throw new Exception('Could not download carousel header media');
                                }

                                $sourceName    = basename(parse_url($headerHandle, PHP_URL_PATH));
                                $cardMediaPath = uniqid('carousel_') . '_' . $sourceName;
                                $filePath      = getFilePath('templateCardHeader') . '/' . $cardMediaPath;

                                File::ensureDirectoryExists(dirname($filePath));
                                File::put($filePath, $mediaResponse->body());

                                $uploadedMedia = $whatsappManager->uploadMedia($mediaLink, $filePath, $accessToken);
                                $cardMediaId    = $uploadedMedia['id'];
                            }

                            $carouselCards[] = [
                                'header'        => $cardHeader,
                                'body'          => $cardBody,
                                'buttons'       => $cardButtons,
                                'media_id'      => $cardMediaId,
                                'media_path'    => $cardMediaPath,
                                'header_format' => strtoupper($cardHeader['format'] ?? 'IMAGE'),
                            ];
                        }

                        continue;
                    }
                }


                $newTemplate = $existingTemplate ?? new Template();

                if (!$existingTemplate) {
                    $newTemplate->user_id                     = $user->id;
                    $newTemplate->whatsapp_account_id         = $whatsappAccount->id;
                    $newTemplate->whatsapp_template_id        = $template['id'];
                    $newTemplate->name                        = $template['name'];
                    $newTemplate->category_id                 = $category->id;
                    $newTemplate->language_id                 = $language->id;
                    $newTemplate->body                        = $body;
                    $newTemplate->header                      = $header;
                    $newTemplate->header_format               = $headerFormat;
                    $newTemplate->header_media                = $headerMedia;
                    $newTemplate->footer                      = $footer;
                    $newTemplate->status                      = metaTemplateStatus($template['status']);
                    $newTemplate->buttons                     = $buttons;
                    $newTemplate->code_expiration_minutes     = null;
                    $newTemplate->add_security_recommendation = $request->add_security_recommendation ? Status::YES : Status::NO;
                    $newTemplate->save();
                }

                foreach ($carouselCards as $card) {
                    $templateCard                = new TemplateCard();
                    $templateCard->template_id   = $newTemplate->id;
                    $templateCard->user_id       = $user->id;
                    $templateCard->media_id      = $card['media_id'];
                    $templateCard->header_format = $card['header_format'];
                    $templateCard->media_path    = $card['media_path'];
                    $templateCard->header        = $card['header'];
                    $templateCard->body          = $card['body'];
                    $templateCard->buttons       = $card['buttons'];
                    $templateCard->save();
                }

                $totalImported++;
            }

            $message = "{$totalImported} templates imported successfully from " . count($templates) . " total templates";
            return responseManager('success', $message, 'success');
        } catch (Exception $ex) {
            return responseManager('error', $ex->getMessage() ?? "Something went to wrong");
        }
    }
}
