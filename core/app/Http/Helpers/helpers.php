<?php

use App\Constants\Status;
use App\Lib\GoogleAuthenticator;
use App\Models\Extension;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use Carbon\Carbon;
use App\Lib\Captcha;
use App\Lib\ClientInfo;
use App\Lib\CurlRequest;
use App\Lib\Export\ExportManager;
use App\Lib\FileManager;
use App\Lib\Export\ImportFileReader;
use App\Models\AiUserSetting;
use App\Models\Contact;
use App\Models\Language;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserApiCredentials;
use App\Models\WhatsappAccount;
use App\Notify\Notify;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

function systemDetails()
{
    $system['name']                = 'ovowpp';
    $system['web_version']         = '2.2';
    $system['admin_panel_version'] = '1.0.1';
    $system['mobile_app_version']  = '2.2';
    $system['android_version']     = '1.0';
    $system['ios_version']         = '1.0';
    $system['flutter_version']     = '1.0';
    return $system;
}

function slug($string)
{
    return Str::slug($string);
}

function verificationCode($length)
{
    if ($length == 0) return 0;
    $min = pow(10, $length - 1);
    $max = (int) ($min - 1) . '9';
    return random_int($min, $max);
}

function getNumber($length = 8)
{
    $characters = '1234567890';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function activeTemplate($asset = false)
{
    $template = session('template') ?? gs('active_template');
    if ($asset) return 'assets/templates/' . $template . '/';
    return 'templates.' . $template . '.';
}

function activeTemplateName()
{
    $template = session('template') ?? gs('active_template');
    return $template;
}

function siteLogo($type = null)
{
    $name = $type ? "/logo_$type.png" : '/logo.png';
    return getImage(getFilePath('logoIcon') . $name);
}

function siteFavicon()
{
    return getImage(getFilePath('logoIcon') . '/favicon.png');
}

function loadReCaptcha()
{
    return Captcha::reCaptcha();
}

function loadCustomCaptcha($width = '100%', $height = 46, $bgColor = '#072d15')
{
    return Captcha::customCaptcha($width, $height, $bgColor);
}

function verifyCaptcha()
{
    return Captcha::verify();
}

function loadExtension($key)
{
    $extension = Extension::where('act', $key)->where('status', Status::ENABLE)->first();
    return $extension ? $extension->generateScript() : '';
}

function getTrx($length = 12)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function getAmount($amount, $length = 2)
{
    $amount = round($amount ?? 0, $length);
    return $amount + 0;
}

function showAmount($amount, $decimal = null, $separate = true, $exceptZeros = false, $currencyFormat = true, $separator = '')
{
    if (!$decimal) {
        $decimal = gs('allow_precision');
    }


    if ($separate && !$separator) {
        $separator = str_replace(['space', 'none'], [' ', ''], gs('thousand_separator'));
    }

    $printAmount = number_format($amount, $decimal, '.', $separator);
    if ($exceptZeros) {
        $exp = explode('.', $printAmount);
        if ($exp[1] * 1 == 0) {
            $printAmount = $exp[0];
        } else {
            $printAmount = rtrim($printAmount, '0');
        }
    }
    if ($currencyFormat) {
        if (gs('currency_format') == Status::CUR_BOTH) {
            return gs('cur_sym') . $printAmount . ' ' . __(gs('cur_text'));
        } elseif (gs('currency_format') == Status::CUR_TEXT) {
            return $printAmount . ' ' . __(gs('cur_text'));
        } else {
            return gs('cur_sym') . $printAmount;
        }
    }
    return $printAmount;
}


function removeElement($array, $value)
{
    return array_diff($array, (is_array($value) ? $value : array($value)));
}

function cryptoQR($wallet)
{
    return "https://api.qrserver.com/v1/create-qr-code/?data=$wallet&size=300x300&ecc=m";
}

function keyToTitle($text)
{
    return ucfirst(preg_replace("/[^A-Za-z0-9 ]/", ' ', $text));
}

function titleToKey($text)
{
    return strtolower(str_replace(' ', '_', $text));
}

function strLimit($title = null, $length = 10)
{
    return Str::limit($title, $length);
}


function getIpInfo()
{
    $ipInfo = ClientInfo::ipInfo();
    return $ipInfo;
}


function osBrowser()
{
    $osBrowser = ClientInfo::osBrowser();
    return $osBrowser;
}


function getTemplates()
{
    $param['purchasecode'] = env("PURCHASECODE");
    $param['website'] = @$_SERVER['HTTP_HOST'] . @$_SERVER['REQUEST_URI'] . ' - ' . env("APP_URL");
    $url = "#";
    $response = CurlRequest::curlPostContent($url, $param);
    if ($response) {
        return $response;
    } else {
        return null;
    }
}

function getPageSections($arr = false)
{
    $jsonUrl = resource_path('views/') . str_replace('.', '/', activeTemplate()) . 'sections.json';
    $sections = json_decode(file_get_contents($jsonUrl));
    if ($arr) {
        $sections = json_decode(file_get_contents($jsonUrl), true);
        ksort($sections);
    }
    return $sections;
}

function getImage($image, $size = null, $isAvatar = false)
{
    $clean = '';
    if (file_exists($image) && is_file($image)) {
        return asset($image) . $clean;
    }
    if ($isAvatar) {
        return asset('assets/images/avatar.jpg');
    }
    if ($size) {
        return route('placeholder.image', $size);
    }
    return asset('assets/images/default.png');
}


function notify($user, $templateName, $shortCodes = null, $sendVia = null, $createLog = true, $pushImage = null)
{
    $globalShortCodes = [
        'site_name' => gs('site_name'),
        'site_currency' => gs('cur_text'),
        'currency_symbol' => gs('cur_sym'),
    ];

    if (gettype($user) == 'array') {
        $user = (object) $user;
    }

    if (is_null($sendVia)) {
        $sendNotificationChannel = [
            'email' => 'email',
            'sms'   => 'sms',
            'push'  => 'push',
        ];
    } else {
        $sendNotificationChannel = $sendVia;
    }

    $mustSendNotificationTemplate = ['PASS_RESET_CODE'];

    if (!in_array($templateName, $mustSendNotificationTemplate)) {

        if (in_array('email', $sendNotificationChannel) && !@$user->en) {
            unset($sendNotificationChannel['email']);
        }

        if (in_array('sms', $sendNotificationChannel) && !@$user->sn) {
            unset($sendNotificationChannel['sms']);
        }

        if (in_array('push', $sendNotificationChannel) && !@$user->pn) {
            unset($sendNotificationChannel['push']);
        }
    }

    $shortCodes = array_merge($shortCodes ?? [], $globalShortCodes);

    $notify               = new Notify($sendNotificationChannel);
    $notify->templateName = $templateName;
    $notify->shortCodes   = $shortCodes;
    $notify->user         = $user;
    $notify->createLog    = $createLog;
    $notify->pushImage    = $pushImage;
    $notify->userColumn   = isset($user->id) ? $user->getForeignKey() : 'user_id';
    $notify->send();
}

function getPaginate($paginate = null)
{

    if (!$paginate) {
        $paginate = request()->paginate ??   gs('paginate_number');
    }

    return is_numeric($paginate) ? $paginate : 20;
}

function getOrderBy($orderBy = null)
{
    if (!$orderBy) {
        $orderBy = request()->order_by ?? 'desc';
    }
    return $orderBy;
}

function paginateLinks($data, $view = null)
{
    $paginationHtml = $data->appends(request()->all())->links($view);
    echo '<div class="pagination-wrapper w-100">' . $paginationHtml . '</div>';
}

function menuActive($routeName, $param = null, $className = 'active')
{

    if (is_array($routeName)) {
        foreach ($routeName as $key => $value) {
            if (request()->routeIs($value)) return $className;
        }
    } elseif (request()->routeIs($routeName)) {
        if ($param) {
            $routeParam = array_values(@request()->route()->parameters ?? []);
            if (strtolower(@$routeParam[0]) == strtolower($param)) return $className;
            else return;
        }
        return $className;
    }
}


function fileUploader($file, $location, $size = null, $old = null, $thumb = null, $filename = null)
{
    $fileManager = new FileManager($file);
    $fileManager->path = $location;
    $fileManager->size = $size;
    $fileManager->old = $old;
    $fileManager->thumb = $thumb;
    $fileManager->filename = $filename;
    $fileManager->upload();
    return $fileManager->filename;
}

function fileManager()
{
    return new FileManager();
}

function getFilePath($key)
{
    return fileManager()->$key()->path;
}

function getFileSize($key)
{
    return fileManager()->$key()->size;
}

function getFileExt($key)
{
    return fileManager()->$key()->extensions;
}

function diffForHumans($date)
{
    $lang = session()->get('lang');
    if (!$lang) {
        $lang = getDefaultLang();
    }
    Carbon::setlocale($lang);

    return Carbon::parse($date)->diffForHumans();
}

function checkSpecialRegex($string)
{
    $regex = '/[+\-*\/%==!=<>]=?|&&|\|\||\.\.|::|->|@|\$|\^|~|\[|\]|\{|\}|\(|\)|;|,|=>|:]/';
    return preg_match($regex, $string);
}

function showDateTime($date, $format = null, $lang = null)
{
    if (!$date) {
        return '-';
    }
    if (!$lang) {
        $lang = session()->get('lang');
        if (!$lang) {
            $lang = getDefaultLang();
        }
    }

    if (!$format) {
        $format = gs('date_format') . ' ' . gs('time_format');
    }

    Carbon::setlocale($lang);
    return Carbon::parse($date)->translatedFormat($format);
}

function getDefaultLang()
{
    return config('app.local') ?? 'en';
}

function getCurrentLang()
{
    return app()->getLocale() ?? "en";
}

function getCurrentLangImage()
{
    $language = Language::whereCode(getCurrentLang())->first();
    if ($language) {
        return $language->image_src;
    }
    return null;
}

function getContent($dataKeys, $singleQuery = false, $limit = null, $orderById = false)
{

    $templateName = activeTemplateName();
    if ($singleQuery) {
        $content = Frontend::where('tempname', $templateName)->where('data_keys', $dataKeys)->orderBy('id', 'desc')->first();
    } else {
        $article = Frontend::where('tempname', $templateName);
        $article->when($limit != null, function ($q) use ($limit) {
            return $q->limit($limit);
        });
        if ($orderById) {
            $content = $article->where('data_keys', $dataKeys)->orderBy('id')->get();
        } else {
            $content = $article->where('data_keys', $dataKeys)->orderBy('id', 'desc')->get();
        }
    }
    return $content;
}

function verifyG2fa($user, $code, $secret = null)
{
    $authenticator = new GoogleAuthenticator();
    if (!$secret) {
        $secret = $user->tsc;
    }
    $oneCode = $authenticator->getCode($secret);
    $userCode = $code;
    if ($oneCode == $userCode) {
        $user->tv = Status::YES;
        $user->save();
        return true;
    } else {
        return false;
    }
}


function urlPath($routeName, $routeParam = null)
{
    if ($routeParam == null) {
        $url = route($routeName);
    } else {
        $url = route($routeName, $routeParam);
    }
    $basePath = route('home');
    $path = str_replace($basePath, '', $url);
    return $path;
}


function showMobileNumber($number)
{
    $length = strlen($number);
    return substr_replace($number, '***', 2, $length - 4);
}

function showEmailAddress($email)
{
    $endPosition = strpos($email, '@') - 1;
    return substr_replace($email, '***', 1, $endPosition);
}


function getRealIP()
{
    $ip = $_SERVER["REMOTE_ADDR"];
    //Deep detect ip
    if (filter_var(@$_SERVER['HTTP_FORWARDED'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_FORWARDED'];
    }
    if (filter_var(@$_SERVER['HTTP_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    }
    if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    if (filter_var(@$_SERVER['HTTP_X_REAL_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    if (filter_var(@$_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    if ($ip == '::1') {
        $ip = '127.0.0.1';
    }

    return $ip;
}

function appendQuery($key, $value)
{
    return request()->fullUrlWithQuery([$key => $value]);
}

function dateSort($a, $b)
{
    return strtotime($a) - strtotime($b);
}

function dateSorting($arr)
{
    usort($arr, "dateSort");
    return $arr;
}

function gs($key = null)
{
    $general = Cache::get('GeneralSetting');
    if (!$general) {
        $general = GeneralSetting::first();
        Cache::put('GeneralSetting', $general);
    }
    if ($key) return @$general->$key;
    return $general;
}
function isImage($string)
{
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
    $fileExtension     = pathinfo($string, PATHINFO_EXTENSION);
    return in_array($fileExtension, $allowedExtensions);
}

function isHtml($string)
{
    if (preg_match('/<.*?>/', $string)) {
        return true;
    } else {
        return false;
    }
}


function convertToReadableSize($size)
{
    preg_match('/^(\d+)([KMG])$/', $size, $matches);
    $size = (int)$matches[1];
    $unit = $matches[2];

    if ($unit == 'G') {
        return $size . 'GB';
    }

    if ($unit == 'M') {
        return $size . 'MB';
    }

    if ($unit == 'K') {
        return $size . 'KB';
    }

    return $size . $unit;
}


function frontendImage($sectionName, $image, $size = null, $seo = false)
{
    if ($seo) {
        return getImage('assets/images/frontend/' . $sectionName . '/seo/' . $image, $size);
    }
    return getImage('assets/images/frontend/' . $sectionName . '/' . $image, $size);
}

function apiResponse(string $remark, string $status, array $message = [], array $data = [], $statusCode = 200): JsonResponse
{
    $response = [
        'remark' => $remark,
        'status' => $status
    ];

    if (count($message)) $response['message'] = $message;
    if (count($data)) $response['data'] = $data;

    return response()->json($response, $statusCode);
}

function exportData($baseQuery, $exportType, $modelName, $printPageSize = "A4 portrait")
{
    try {
        return (new ExportManager($baseQuery, $modelName, $exportType, $printPageSize))->export();
    } catch (Exception $ex) {
        $notify[] = ['error', $ex->getMessage()];
        return back()->withNotify($notify);
    }
}

function os(): array
{
    return [
        'windows',
        'windows 10',
        'windows 7',
        'windows 8',
        'windows xp' . 'linux',
        'apple',
        'android',
        'ubuntu',
    ];
}

function supportedDateFormats(): array
{
    return [
        'Y-m-d',
        'd-m-Y',
        'd/m/Y',
        'm-d-Y',
        'm/d/Y',
        'D, M j, Y',
        'l, F j, Y',
        'F j, Y',
        'M j, Y'
    ];
}
function supportedTimeFormats(): array
{
    return [
        'H:i:s',
        'H:i',
        'h:i A',
        'g:i a',
        'g:i:s a'
    ];
}
function supportedThousandSeparator(): array
{
    return [
        "," => "Comma",
        "." => "Dot",
        "'" => "Apostrophe",
        "space" => "Space",
        "none" => "None",
    ];
}

function templateHeaderTypes()
{
    return [
        'text' => 'TEXT',
        'image' => 'IMAGE',
        'video' => 'VIDEO',
        'document' => 'DOCUMENT'
    ];
}

function templateCardHeaderTypes()
{
    return [
        'image' => 'IMAGE',
        'video' => 'VIDEO'
    ];
}

function metaTemplateStatus($status)
{
    $metaTemplateStatus = [
        'PENDING' => Status::TEMPLATE_PENDING,
        'APPROVED' => Status::TEMPLATE_APPROVED,
        'REJECTED' => Status::TEMPLATE_REJECTED,
        'DISABLED' => Status::TEMPLATE_DISABLED
    ];

    return $metaTemplateStatus[$status];
}

function messageStatus($status)
{
    $messageStatus = [
        'sent'      => Status::SENT,
        'delivered' => Status::DELIVERED,
        'read'      => Status::READ,
        'failed'    => Status::FAILED
    ];

    return $messageStatus[$status];
}

function getPlanPurchasePrice($plan, $recurringType)
{
    return $recurringType == Status::MONTHLY ? $plan->monthly_price : $plan->yearly_price;
}

function applyCouponDiscount($coupon, $amount)
{
    if ($coupon->type == Status::COUPON_TYPE_PERCENTAGE) {
        return $amount - ($amount * $coupon->amount / 100);
    } else {
        return $amount - $coupon->amount;
    }
}

function userReferralCommission($user, $amount = 0)
{
    $referrer = User::active()->find($user->ref_by);
    $referralPercentage = gs('referral_amount_percentage');
    $commissionAmount = ($amount * $referralPercentage) / 100;

    if (!$referrer || $referralPercentage <= 0 || $commissionAmount <= 0) return false;
    $referrer->balance +=
        $commissionAmount;
    $referrer->save();

    // transaction
    $transaction = new Transaction();
    $transaction->user_id = $referrer->id;
    $transaction->amount = $commissionAmount;
    $transaction->post_balance = $referrer->balance;
    $transaction->charge = 0;
    $transaction->trx_type = '+';
    $transaction->trx = getTrx();
    $transaction->remark = 'referral_commission';
    $transaction->details = 'Referral Commission';
    $transaction->save();

    notify($referrer, 'REFERRAL_COMMISSION', [
        'amount' => showAmount($commissionAmount, currencyFormat: false),
        'user' => $user->username,
        'trx' => $transaction->trx,
        'remark' => $transaction->remark,
        'post_balance' => showAmount($referrer->balance, currencyFormat: false)
    ]);


    return true;
}

function templateBodyParams($body)
{
    $matches = [];
    preg_match_all('/\{\{\d+\}\}/', $body, $matches);
    return count($matches[0]);
}

function variableShortCodes()
{
    return ['{{ contactName }}', '{{ contactMobile }}', '{{ userName }}', '{{ userMobile }}'];
}

function getContact($number)
{
    $contact = Contact::with('conversation')->whereRaw("CONCAT(mobile_code, mobile) = ?", [$number])->first();
    return $contact;
}

function setCodeValue($code, $contact)
{
    $user = getParentUser();

    switch ($code) {
        case '{{ contactName }}':
            $result = $contact ? $contact->fullName : ' Sir';
            break;
        case '{{ contactMobile }}':
            $result = $contact->mobileNumber;
            break;
        case '{{ userName }}':
            $result = $user->fullName;
            break;
        case '{{ userMobile }}':
            $result = $user->mobileNumber;
            break;
        default:
            $result = " ";
            break;
    }

    return $result;
}

function parseTemplateParams(array $params, $contact)
{
    $updatedParams = [];

    foreach ($params as $param) {
        $text = $param['text'] ?? '';

        if (preg_match('/\{\{.*\}\}/', $text)) {
            $text = setCodeValue($text, $contact);
        }

        $updatedParams[] = [
            'type' => 'text',
            'text' => $text,
        ];
    }

    return $updatedParams;
}

function importFileReader($file, $columns, $uniqueColumns = [], $dataInsert = true, $modelClass =
Contact::class, $references = [])
{
    $fileRead                 = new ImportFileReader($file, $modelClass);
    $fileRead->columns        = $columns;
    $fileRead->uniqueColumns  = $uniqueColumns;
    $fileRead->dataInsertMode = $dataInsert;
    $fileRead->references     = $references;
    $fileRead->readFile();
    return $fileRead;
}

function isApiRequest()
{
    return request()->is('api/*');
}
function isExternalApiRequest()
{
    return request()->is('external-api/*');
}
function isAjaxRequest()
{
    return request()->ajax();
}

function responseManager(
    string $remark,
    string $message,
    string $responseType = 'error',
    array $responseData = [],
    array $igNoreOnApi = []
) {
    if (isApiRequest() || isAjaxRequest()) {
        $notify[] = $message;
        $ignoreForApi = array_merge($igNoreOnApi, ['view', 'pageTitle']);
        $responseData = array_diff_key(
            $responseData,
            array_flip($ignoreForApi)
        );
        $responseDataToSnake = array_combine(
            array_map(function ($key) {
                return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $key));
            }, array_keys($responseData)),
            array_values($responseData)
        );
        return apiResponse($remark, $responseType, $notify, $responseDataToSnake);
    }
    if (array_key_exists('view', $responseData)) {
        return view($responseData['view'], $responseData);
    }
    $notify[] = [$responseType, $message];
    return back()->withNotify($notify);
}

function getParentUser()
{
    $user = auth()->user();
    if ($user) {
        return $user->is_agent ? $user->parent : $user;
    } else {
        return null;
    }
}

function isParentUser()
{
    $user = auth()->user();
    return $user->is_agent ? false : true;
}

function activeClass($condition, $className = 'active')
{
    return $condition ? $className : '';
}

function printLimit($limit)
{
    if ($limit == Status::UNLIMITED) {
        return __('Unlimited');
    }

    if ($limit == 0) {
        return __('Not Available');
    }

    return number_format($limit);
}

function userSubscriptionExpiredCheck($user = null)
{
    $user = is_null($user) ? getParentUser() : $user;
    return !empty($user->plan_expired_at) && !now()->parse($user->plan_expired_at)->isPast();
}

function featureAccessLimitCheck($feature, $limit = 1)
{
    if ($feature == Status::UNLIMITED) return true;

    if ($feature && $feature >= $limit) return true;

    return false;
}

function decrementFeature($user, $feature, $count = 1)
{
    if (!Schema::hasColumn('users', $feature)) {
        return;
    }

    if ($user->$feature == Status::UNLIMITED) return;

    $user->decrement($feature, $count);
}

function getFeatureLimit($feature, $count = 1, $user = null)
{
    $authUser = getParentUser();

    if (!$user) {
        $user = $authUser;
    }

    if (!Schema::hasColumn('users', $feature)) return;

    if ($feature = Status::UNLIMITED || $user->$feature == Status::UNLIMITED) return Status::UNLIMITED;

    return getAmount($user->$feature) + getAmount($count);
}

function getWhatsappAccountId($user)
{
    return request()->whatsapp_account_id ? request()->whatsapp_account_id : $user->currentWhatsapp()?->id;
}
function getWhatsappAccount($user)
{
    if (request()->whatsapp_account_id) {
        return WhatsappAccount::where('user_id', $user->id)->where('id', request()->whatsapp_account_id)->first();
    }

    return $user->currentWhatsapp();
}

function strPlural(int $value, string $label)
{
    return $value . ' ' . ($value == 1 ? $label : Str::plural($label));
}

function createAiSetting($user)
{
    $aiSetting          = new AiUserSetting();
    $aiSetting->user_id = $user->id;
    $aiSetting->save();
}

function getIntMessageType($messageType)
{
    return match ($messageType) {
        'text'        => Status::TEXT_TYPE_MESSAGE,
        'image'       => Status::IMAGE_TYPE_MESSAGE,
        'video'       => Status::VIDEO_TYPE_MESSAGE,
        'document'    => Status::DOCUMENT_TYPE_MESSAGE,
        'audio'       => Status::AUDIO_TYPE_MESSAGE,
        'url'         => Status::URL_TYPE_MESSAGE,
        'button'      => Status::BUTTON_TYPE_MESSAGE,
        'location'    => Status::LOCATION_TYPE_MESSAGE,
        'list'        => Status::LIST_TYPE_MESSAGE,
        'interactive' => Status::REPLY_TYPE_MESSAGE,
        default => null
    };
}

function getNodeType($type)
{
    return match ($type) {
        'textMessage'  => Status::NODE_TYPE_TEXT,
        'sendImage'    => Status::NODE_TYPE_IMAGE,
        'sendVideo'    => Status::NODE_TYPE_VIDEO,
        'sendDocument' => Status::NODE_TYPE_DOCUMENT,
        'sendAudio'    => Status::NODE_TYPE_AUDIO,
        'sendList'     => Status::NODE_TYPE_LIST,
        'sendCtaUrl'   => Status::NODE_TYPE_CTA_URL,
        'sendLocation' => Status::NODE_TYPE_LOCATION,
        'sendButton'   => Status::NODE_TYPE_BUTTON,
        default => null
    };
}

function getNodeMediaIntType($type)
{
    return match ($type) {
        'image'     => Status::NODE_MEDIA_IMAGE,
        'video'     => Status::NODE_MEDIA_VIDEO,
        'audio'     => Status::NODE_MEDIA_AUDIO,
        'document'  => Status::NODE_MEDIA_DOCUMENT,
    };
}

function getNodeMediaStringType($int)
{
    return [
        Status::NODE_MEDIA_IMAGE    => 'image',
        Status::NODE_MEDIA_VIDEO    => 'video',
        Status::NODE_MEDIA_AUDIO    => 'audio',
        Status::NODE_MEDIA_DOCUMENT => 'document',
    ][$int];
}

function getTriggerType($type)
{
    return match ($type) {
        'new_message' => Status::FLOW_TRIGGER_NEW_MESSAGE,
        'keyword_match' => Status::FLOW_TRIGGER_KEYWORD_MATCH,
        default => null
    };
}

function responseBack(bool $success, $message)
{
    return response()->json([
        'success' => $success,
        'message' => $message
    ]);
}

function makeCtaUrlFromProduct($product)
{
    $productImage = null;

    if (
        isset($product['images']) &&
        is_array($product['images']) &&
        !empty($product['images'][0]['src'])
    ) {
        $imageUrl = $product['images'][0]['src'];

        $extension = strtolower(pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION));

        if (in_array($extension, ['jpeg', 'png'])) {
            $productImage = $imageUrl;
        }
    }

    if ($productImage) {
        $header = [
            'type'  => 'image',
            'image' => [
                'link' => $productImage
            ],
        ];
    } else {
        $header = [
            'type' => 'text',
            'text' => $product['name'],
        ];
    }

    $body = [
        'text' => strLimit(strip_tags($product['description']), 100),
    ];

    $action = [
        'name'       => 'cta_url',
        'parameters' => [
            'display_text' => 'Visit Product',
            'url'          => $product['permalink'],
        ],
    ];

    return [
        'type'   => 'cta_url',
        'header' => $header,
        'body'   => $body,
        'action' => $action,
    ];
}

function makeCtaUrlFromCreatedOrder($order)
{
    $header = [
        'type' => 'text',
        'text' => trans('Order Information'),
    ];

    $body = [
        'text' => (isset($order['woo_response'])
            && is_array($order['woo_response'])
            && isset($order['woo_response']['line_items'])
            && is_array($order['woo_response']['line_items'])
            && !empty($order['woo_response']['line_items'][0]['name']))
            ? $order['woo_response']['line_items'][0]['name']
            : null
    ];

    $action = [
        'name'         => 'cta_url',
        'parameters'   => [
            'display_text' => 'Visit Order',
            'url'          => $order['order_url']
        ]
    ];

    $interactive = [
        'type'  => 'cta_url',
        'header' => $header,
        'body'  => $body,
        'action' => $action
    ];

    return $interactive;
}


function getUserFromExternalAPIAccess()
{
    $credentials = UserApiCredentials::where('client_id', request()->headers->get('client-id'))
        ->where('client_secret', request()->headers->get('client-secret'))
        ->first();
    return  User::active()->find(@$credentials->user_id);
}


function isRtl()
{
    return config('app.locale') == 'ar';
}