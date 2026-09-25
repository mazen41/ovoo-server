<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\Campaign;
use App\Models\CampaignContact;
use App\Models\Contact;
use App\Models\ContactList;
use App\Models\ContactTag;
use App\Models\Deposit;
use App\Models\DeviceToken;
use App\Models\Floater;
use App\Models\Flow;
use App\Models\Form;
use App\Models\Message;
use App\Models\ShortLink;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserApiCredentials;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle = 'Dashboard';
        $user      = getParentUser();

        $widget['total_contact']          = Contact::where('user_id', $user->id)->count();
        $widget['total_tag']              = ContactTag::where('user_id', $user->id)->count();
        $widget['total_list']             = ContactList::where('user_id', $user->id)->count();
        $widget['total_campaign']         = Campaign::where('user_id', $user->id)->count();
        $widget['total_shortlink']        = ShortLink::where('user_id', $user->id)->count();
        $widget['total_floater']          = Floater::where('user_id', $user->id)->count();
        $widget['total_referrer']         = User::where('ref_by', $user->id)->count();
        $widget['total_flow']             = Flow::where('user_id', $user->id)->count();
        $widget['total_deposit_amount']   = Deposit::successful()->where('user_id', $user->id)->sum('amount');
        $widget['total_withdraw_amount']  = Withdrawal::approved()->where('user_id', $user->id)->sum('amount');
        $widget['total_transaction']      = Transaction::where('user_id', $user->id)->count();

        $widget['total_campaign_message'] = CampaignContact::whereHas('campaign', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->count();

        $widget['total_campaign_message_delivered'] = Message::whereHas('campaign', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', Status::DELIVERED)->count();

        $widget['total_campaign_message_failed'] = Message::whereHas('campaign', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', Status::FAILED)->count();

        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        $contacts = Contact::where('user_id', $user->id)
            ->orderBy('id', 'desc')
            ->take(5)
            ->get();

        return view('Template::user.dashboard', compact('pageTitle', 'widget', 'user', 'transactions', 'contacts'));
    }

    public function depositHistory(Request $request)
    {
        $user      = auth()->user();
        $pageTitle = 'Deposit History';
        $deposits  = $user->deposits()->searchable(['trx'])->filter(['status'])->dateFilter()->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function show2faForm()
    {
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . gs('site_name'), $secret);
        $pageTitle = '2FA Security';

        return view('Template::user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = Status::ENABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = Status::DISABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    function userDevelopmentCredential()
    {

        $pageTitle = 'Developer Tool';
        $user      = getParentUser();

        $userApiCredential = UserApiCredentials::where('user_id', $user->id)->first();

        if (!$userApiCredential) {
            $userApiCredential                = new UserApiCredentials();
            $userApiCredential->user_id       = $user->id;
            $userApiCredential->client_id     = $this->generateCode('client_id', "ci_");
            $userApiCredential->client_secret = $this->generateCode('client_secret', "cs_");
            $userApiCredential->save();
        }

        return view('Template::user.development_credential', compact('pageTitle', 'userApiCredential'));
    }

    public function transactions()
    {
        $pageTitle    = 'Transactions';
        $remarks      = Transaction::distinct('remark')->orderBy('remark')->get('remark');
        $transactions = Transaction::where('user_id', auth()->id())
            ->searchable(['trx'])
            ->filter(['trx_type', 'remark'])
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());
        return view('Template::user.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function kycForm()
    {
        if (auth()->user()->kv == Status::KYC_PENDING) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }
        if (auth()->user()->kv == Status::KYC_VERIFIED) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }
        $pageTitle = 'KYC Verification';
        $form = Form::where('act', 'kyc')->first();

        return view('Template::user.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData()
    {
        $user = auth()->user();
        $pageTitle = 'KYC Data';
        abort_if($user->kv == Status::VERIFIED, 403);
        return view('Template::user.kyc.info', compact('pageTitle', 'user'));
    }

    public function kycSubmit(Request $request)
    {
        $form = Form::where('act', 'kyc')->firstOrFail();
        $formData = $form->form_data;
        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $user = auth()->user();
        foreach (@$user->kyc_data ?? [] as $kycData) {
            if ($kycData->type == 'file') {
                fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
            }
        }
        $userData = $formProcessor->processFormData($request, $formData);
        $user->kyc_data = $userData;
        $user->kyc_rejection_reason = null;
        $user->kv = Status::KYC_PENDING;
        $user->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function userData()
    {
        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $pageTitle  = 'User Data';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('Template::user.user_data', compact('pageTitle', 'user', 'countries', 'mobileCode'));
    }

    public function userDataSubmit(Request $request)
    {

        $user = auth()->user();

        if ($user->_complete == Status::YES) {
            return to_route('user.home');
        }

        $countryData  = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        $request->validate([
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'mobile_code'  => 'required|in:' . $mobileCodes,
            'username'     => 'required|unique:users|min:6',
            'mobile'       => ['required', 'regex:/^([0-9]*)$/', Rule::unique('users')->where('dial_code', $request->mobile_code)],
        ]);


        if (preg_match("/[^a-z0-9_]/", trim($request->username))) {
            $notify[] = ['info', 'Username can contain only small letters, numbers and underscore.'];
            $notify[] = ['error', 'No special character, space or capital letters in username.'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        $user->country_code = $request->country_code;
        $user->mobile       = $request->mobile;
        $user->username     = $request->username;

        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip = $request->zip;
        $user->country_name = @$request->country;
        $user->dial_code = $request->mobile_code;

        $user->profile_complete = Status::YES;
        $user->save();

        return to_route('user.home');
    }

    public function addDeviceToken(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->errors()->all()];
        }

        $deviceToken = DeviceToken::where('token', $request->token)->first();

        if ($deviceToken) {
            return ['success' => true, 'message' => 'Already exists'];
        }

        $deviceToken          = new DeviceToken();
        $deviceToken->user_id = auth()->user()->id;
        $deviceToken->token   = $request->token;
        $deviceToken->is_app  = Status::NO;
        $deviceToken->save();

        return ['success' => true, 'message' => 'Token saved successfully'];
    }

    public function downloadAttachment($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function notificationSetting()
    {
        $pageTitle = 'Notification Setting';
        $user      = auth()->user();
        return view('Template::user.notification_setting', compact('pageTitle', 'user'));
    }

    public function notificationSettingsUpdate(Request $request)
    {
        $user     = auth()->user();
        $user->en = $request->en ? Status::ENABLE : Status::DISABLE;
        $user->sn = $request->sn ? Status::ENABLE : Status::DISABLE;
        $user->pn = $request->pn ? Status::ENABLE : Status::DISABLE;
        $user->save();

        $notify[] = ['success', 'Notification settings updated successfully'];

        return back()->withNotify($notify);
    }



    private function generateCode($column, $prefix)
    {
        do {
            $code = $prefix . getTrx(20);
        } while (UserApiCredentials::where($column, $code)->exists());

        return $code;
    }
}
