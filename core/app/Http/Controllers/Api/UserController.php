<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\Campaign;
use App\Models\Contact;
use App\Models\Conversation;
use App\Models\DeviceToken;
use App\Models\Form;
use App\Models\Message;
use App\Models\NotificationLog;
use App\Models\Transaction;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function dashboard()
    {
        $user         = auth()->user();
        $parentUser   = getParentUser();
        $notify[] = 'User Dashboard';
        
        $campaigns                     = Campaign::where('user_id', $parentUser->id);
        $widget['active_campaign']     = (clone $campaigns)->where('status', Status::CAMPAIGN_RUNNING)->count();
        $widget['completed_campaign']  = (clone $campaigns)->where('status', Status::CAMPAIGN_COMPLETED)->count();

        $totalMessages              = Message::where('user_id', $parentUser->id)->where('type', Status::MESSAGE_SENT);
        $widget['total_message']     = (clone $totalMessages)->count();
        $widget['sent_message']     = (clone $totalMessages)->where('status',Status::SENT)->count();

        $now = Carbon::now();
        $widget['contact_count'] = Contact::where('user_id', $parentUser->id)
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        $topConversation = Conversation::where('user_id', $parentUser->id)
            ->withCount('messages')
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->orderByDesc('messages_count')
            ->first();

        $widget['top_contact'] = $topConversation?->contact?->fullName ?? 'N/A';
        $widget['top_contact_message'] = $topConversation?->messages_count ?? 0;

        $totalConversations         = Conversation::where('user_id', $parentUser->id);
        $totalConversationsCount    = (clone $totalConversations)->count();

        $conversationWithMessages = (clone $totalConversations)
            ->whereHas('messages', function ($query) {
                $query->where('type', Status::MESSAGE_RECEIVED);
            })->count();

        $widget['chat_completion_rate'] = $totalConversationsCount > 0
            ? round(($conversationWithMessages / $totalConversationsCount) * 100)
            : 0;

        $widget['subscription']   = $parentUser->purchases()->where('plan_id', $parentUser->plan_id)->with('plan')->first();
        $widget['wallet_balance'] = $parentUser->balance;
        
        if ($user->is_agent) {
            $widget['permissions'] = $user->agentPermissions()->pluck('name')->toArray();
        }
        
        return apiResponse("dashboard", "success", $notify, [
            'user'        => $user,
            'widget'      => $widget,
            'profilePath' => getFilePath('userProfile'),
        ]);
    }

    public function userDataSubmit(Request $request)
    {
        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            $notify[] = 'You\'ve already completed your profile';
            return apiResponse("already_completed", "error", $notify);
        }

        $countryData  = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        $validator = Validator::make($request->all(), [
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'mobile_code'  => 'required|in:' . $mobileCodes,
            'username'     => 'required|unique:users|min:6',
            'mobile'       => ['required', 'regex:/^([0-9]*)$/', Rule::unique('users')->where('dial_code', $request->mobile_code)],
        ]);

        if ($validator->fails()) {
            return apiResponse("validation_error", "error", $validator->errors()->all());
        }


        if (preg_match("/[^a-z0-9_]/", trim($request->username))) {
            $notify[] = 'No special character, space or capital letters in username';
            return apiResponse("validation_error", "error", $notify);
        }

        $user->country_code = $request->country_code;
        $user->mobile       = $request->mobile;
        $user->username     = $request->username;

        $user->address      = $request->address;
        $user->city         = $request->city;
        $user->state        = $request->state;
        $user->zip          = $request->zip;
        $user->country_name = @$request->country;
        $user->dial_code    = $request->mobile_code;

        $user->profile_complete = Status::YES;
        $user->save();

        $notify[] = 'Profile completed successfully';

        return apiResponse("profile_completed", "success", $notify, [
            'user' => $user
        ]);
    }

    public function kycForm()
    {
        if (auth()->user()->kv == Status::KYC_PENDING) {
            $notify[] = 'Your KYC is under review';
            return apiResponse("under_review", "error", $notify);
        }
        if (auth()->user()->kv == Status::KYC_VERIFIED) {
            $notify[] = 'You are already KYC verified';
            return apiResponse("already_verified", "error", $notify);
        }

        $form     = Form::where('act', 'kyc')->first();
        $notify[] = 'KYC field is below';
        return apiResponse("kyc_form", "success", $notify, [
            'form' => $form->form_data
        ]);
    }

    public function kycSubmit(Request $request)
    {
        $form = Form::where('act', 'kyc')->first();
        if (!$form) {
            $notify[] = 'Invalid KYC request';
            return apiResponse("invalid_request", "error", $notify);
        }

        $formData       = $form->form_data;
        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);

        $validator = Validator::make($request->all(), $validationRule);

        if ($validator->fails()) {
            return apiResponse("validation_error", "error", $validator->errors()->all());
        }
        $user = auth()->user();
        foreach (@$user->kyc_data ?? [] as $kycData) {
            if ($kycData->type == 'file') {
                fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
            }
        }
        $userData = $formProcessor->processFormData($request, $formData);

        $user->kyc_data             = $userData;
        $user->kyc_rejection_reason = null;
        $user->kv                   = Status::KYC_PENDING;
        $user->save();

        $notify[] = 'KYC data submitted successfully';
        return apiResponse("kyc_submitted", "success", $notify);
    }

    public function depositHistory(Request $request)
    {
        $deposits = auth()->user()->deposits();
        if ($request->search) {
            $deposits = $deposits->where('trx', $request->search);
        }
        $deposits = $deposits->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        $notify[] = 'Deposit data';

        return apiResponse("deposits", "success", $notify, [
            'deposits' => $deposits
        ]);
    }

    public function transactions(Request $request)
    {
        $remarks      = Transaction::distinct('remark')->get('remark');
        $transactions = Transaction::where('user_id', auth()->id());

        if ($request->search) {
            $transactions = $transactions->where('trx', $request->search);
        }

        if ($request->type) {
            $type         = $request->type == 'plus' ? '+' : '-';
            $transactions = $transactions->where('trx_type', $type);
        }

        if ($request->remark) {
            $transactions = $transactions->where('remark', $request->remark);
        }

        $transactions = $transactions->orderBy('id', 'desc')->paginate(getPaginate());
        $notify[]     = 'Transactions data';

        return apiResponse("transactions", "success", $notify, [
            'transactions' => $transactions,
            'remarks'      => $remarks,
        ]);
    }

    public function submitProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname'  => 'required',
            'image'     => ['nullable', 'image', 'max:2048', new FileTypeValidate(['jpg', 'jpeg', 'png'])]
        ], [
            'firstname.required' => 'The first name field is required',
            'lastname.required'  => 'The last name field is required'
        ]);

        if ($validator->fails()) {
            return apiResponse("validation_error", "error", $validator->errors()->all());
        }
        $user            = auth()->user();

        if ($request->hasFile('profile_image')) {
            try {
                $old         = $user->image;
                $user->image = fileUploader($request->profile_image, getFilePath('userProfile'), getFileSize('userProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = 'Couldn\'t upload your image';
                return apiResponse("upload_error", "error", $notify);
            }
        }

        $user->firstname = $request->firstname;
        $user->lastname  = $request->lastname;
        $user->address   = $request->address;
        $user->city      = $request->city;
        $user->state     = $request->state;
        $user->zip       = $request->zip;
        $user->save();
        $notify[] = 'Profile updated successfully';

        return apiResponse("profile_updated", "success", $notify);
    }

    public function submitPassword(Request $request)
    {
        $passwordValidation = Password::min(6);
        if (gs('secure_password')) {
            $passwordValidation = $passwordValidation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', $passwordValidation]
        ]);

        if ($validator->fails()) {
            return apiResponse("validation_error", "error", $validator->errors()->all());
        }

        $user = auth()->user();
        if (Hash::check($request->current_password, $user->password)) {
            $password       = Hash::make($request->password);
            $user->password = $password;
            $user->save();
            $notify[] = 'Password changed successfully';
            return apiResponse("password_changed", "success", $notify);
        } else {
            $notify[] = 'The password doesn\'t match!';
            return apiResponse("not_match", "validation_error", $notify);
        }
    }

    public function addDeviceToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);
        if ($validator->fails()) {
            return apiResponse("validation_error", "error", $validator->errors()->all());
        }

        $deviceToken = DeviceToken::where('token', $request->token)->first();

        if ($deviceToken) {
            $notify[] = 'Token already exists';
            return apiResponse("token_exists", "error", $notify);
        }

        $deviceToken          = new DeviceToken();
        $deviceToken->user_id = auth()->user()->id;
        $deviceToken->token   = $request->token;
        $deviceToken->is_app  = Status::YES;
        $deviceToken->save();

        $notify[] = 'Token saved successfully';
        return apiResponse("token_saved", "success", $notify);
    }


    public function show2faForm()
    {
        $ga        = new GoogleAuthenticator();
        $user      = auth()->user();
        $secret    = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . gs('site_name'), $secret);
        $notify[]  = '2FA Qr';

        return apiResponse("2fa_qr", "success", $notify, [
            'secret'      => $secret,
            'qr_code_url' => $qrCodeUrl,
        ]);
    }

    public function create2fa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret' => 'required',
            'code'   => 'required',
        ]);

        if ($validator->fails()) {
            return apiResponse("validation_error", "error", $validator->errors()->all());
        }

        $user     = auth()->user();
        $response = verifyG2fa($user, $request->code, $request->secret);
        if ($response) {
            $user->tsc = $request->secret;
            $user->ts  = Status::ENABLE;
            $user->save();

            $notify[] = 'Google authenticator activated successfully';
            return apiResponse("2fa_qr", "success", $notify);
        } else {
            $notify[] = 'Wrong verification code';
            return apiResponse("wrong_verification", "error", $notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            return apiResponse("validation_error", "error", $validator->errors()->all());
        }

        $user     = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts  = Status::DISABLE;
            $user->save();
            $notify[] = 'Two factor authenticator deactivated successfully';

            return apiResponse("2fa_qr", "success", $notify);
        } else {
            $notify[] = 'Wrong verification code';
            return apiResponse("wrong_verification", "error", $notify);
        }
    }

    public function pushNotifications()
    {
        $notifications = NotificationLog::where('user_id', auth()->id())->where('sender', 'firebase')->orderBy('id', 'desc')->paginate(getPaginate());
        $notify[]      = 'Push notifications';
        return apiResponse("notifications", "success", $notify, [
            'notifications' => $notifications,
        ]);
    }

    public function pushNotificationsRead($id)
    {
        $notification = NotificationLog::where('user_id', auth()->id())->where('sender', 'firebase')->find($id);
        if (!$notification) {
            $notify[] = 'Notification not found';
            return apiResponse("notification_not_found", "error", $notify);
        }
        $notify[]                = 'Notification marked as read successfully';
        $notification->user_read = 1;
        $notification->save();

        return apiResponse("notification_read", "success", $notify);
    }

    public function userInfo()
    {
        $notify[] = 'User information';
        return apiResponse("user_info", "success", $notify, [
            'user' => auth()->user()
        ]);
    }

    public function deleteAccount()
    {
        $user             = auth()->user();
        $user->is_deleted = Status::YES;
        $user->save();

        $user->tokens()->delete();

        $notify[] = 'Account deleted successfully';
        return apiResponse("account_deleted", "success", $notify);
    }
}
