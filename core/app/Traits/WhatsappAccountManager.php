<?php

namespace App\Traits;

use App\Constants\Status;
use App\Models\Template;
use App\Models\WhatsappAccount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

trait WhatsappAccountManager
{
    use WhatsappManager;

    public function whatsappAccounts()
    {
        $pageTitle = "Manage WhatsApp Account";
        $user = getParentUser();
        $view = 'Template::user.whatsapp.accounts';
        $whatsappAccountsQuery = WhatsappAccount::where('user_id', $user->id)->orderBy('is_default', 'desc');

        if (isApiRequest()) {
            $whatsappAccounts = $whatsappAccountsQuery->get();
        } else {
            $whatsappAccounts = $whatsappAccountsQuery->paginate(getPaginate(10));
        }

        return responseManager("whatsapp_accounts", $pageTitle, "success", [
            'pageTitle' => $pageTitle,
            'view' => $view,
            'whatsappAccounts' => $whatsappAccounts,
            'accountLimit' => featureAccessLimitCheck($user->account_limit)
        ]);
    }

    public function storeWhatsappAccount(Request $request)
    {

        $request->validate([
            'whatsapp_number' => 'required',
            'whatsapp_business_account_id' => 'required',
            'phone_number_id' => 'required',
            'meta_access_token' => 'required',
            'meta_app_id' => 'required',
            'is_coexistence' => 'nullable|boolean',
        ]);

        $user = getParentUser();
        if (!featureAccessLimitCheck($user->account_limit)) {
            $message = "You have reached the maximum limit of WhatsApp account. Please upgrade your plan.";
            return responseManager("whatsapp_error", $message, "error");
        }

        $accountExists = WhatsappAccount::where('phone_number_id', $request->phone_number_id)
            ->orWhere('whatsapp_business_account_id', $request->whatsapp_business_account_id)
            ->exists();

        if ($accountExists) {
            $message = 'This account already has been registered to our system';
            return responseManager("whatsapp_error", $message, "error");
        }

        try {
            $whatsappData = $this->verifyWhatsappCredentials($request->whatsapp_business_account_id, $request->meta_access_token);
        } catch (Exception $ex) {
            return responseManager("whatsapp_error", $ex->getMessage());
        }

        $whatsAccountData = $whatsappData['data'];

        if ($whatsAccountData['code_verification_status'] != 'VERIFIED') {
            $notify[] = ['info', 'Your whatsapp business account is not verified. Please create a permanent access token.'];
            if (isApiRequest()) {
                $notify[] = 'Your whatsapp business account is not verified. Please create a permanent access token.';
            }
        }

        $whatsappAccount = new WhatsappAccount();
        $whatsappAccount->user_id = $user->id;
        $whatsappAccount->phone_number_id = $whatsAccountData['id'];
        $whatsappAccount->phone_number = $request->whatsapp_number;
        $whatsappAccount->business_name = $whatsAccountData['verified_name'];
        $whatsappAccount->access_token = $request->meta_access_token;
        $whatsappAccount->code_verification_status = $whatsAccountData['code_verification_status'];
        $whatsappAccount->whatsapp_business_account_id = $request->whatsapp_business_account_id;
        $whatsappAccount->meta_app_id = $request->meta_app_id;
        $whatsappAccount->is_default = WhatsappAccount::where('user_id', $user->id)->count() ? Status::NO : Status::YES;
        $whatsappAccount->is_coexistence = $request->is_coexistence ? Status::YES : Status::NO;

        if ($whatsappAccount->is_coexistence) {
            // Already registered against the WhatsApp Business app on the phone.
            $whatsappAccount->platform_type        = 'SMB_APP';
            $whatsappAccount->phone_number_status  = 'CONNECTED';
        }

        $whatsappAccount->save();

        decrementFeature($user, 'account_limit');

        if (isApiRequest()) {
            $notify[] = "WhatsApp account added successfully";
            return apiResponse("whatsapp_success", "success", $notify, [
                'whatsappAccount' => $whatsappAccount
            ]);
        }

        //connect whatsapp phone number 

        // Posting /register against a coexistence number would fail, or deregister the handset.
        if ($whatsappAccount->is_coexistence) {
            $notify[] = ["success", "WhatsApp account added successfully"];
            return to_route('user.whatsapp.account.index')->withNotify($notify);
        }

        try {
            $token = $whatsappAccount->access_token;
            $phoneNumberId = $whatsappAccount->phone_number_id;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ])->post('https://graph.facebook.com/v22.0/' . $phoneNumberId . '/register', [
                'messaging_product' => 'whatsapp',
                'pin' => '123456',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['success']) && $data['success'] == true) {
                    $whatsappAccount->phone_number_status = "CONNECTED";
                    $whatsappAccount->save();
                }
            }
        } catch (Exception $ex) {
            return responseManager("whatsapp_error", $ex->getMessage());
        }

        $notify[] = ["success", "WhatsApp account added successfully"];
        return to_route('user.whatsapp.account.index')->withNotify($notify);
    }

    public function whatsappAccountVerificationCheck($accountId)
    {
        $user = getParentUser();
        $whatsappAccount = WhatsappAccount::where('user_id', $user->id)->findOrFailWithApi("whatsapp account", $accountId);

        try {
            $whatsappData = $this->verifyWhatsappCredentials($whatsappAccount->whatsapp_business_account_id, $whatsappAccount->access_token);

            if ($whatsappData['data']['verified_name'] && $whatsappData['data']['display_phone_number']) {
                $whatsappAccount->business_name = $whatsappData['data']['verified_name'];
                $whatsappAccount->phone_number = $whatsappData['data']['display_phone_number'];
                $whatsappAccount->save();
            }
        } catch (Exception $ex) {
            return responseManager("whatsapp_error", $ex->getMessage());
        }

        $message = "WhatsApp account verification status updated successfully";
        return responseManager("verification_status", $message, "success");
    }
    public function whatsappPhoneNumberVerificationCheck($accountId)
    {
        $user = getParentUser();
        $whatsappAccount = WhatsappAccount::where('user_id', $user->id)->findOrFailWithApi("whatsapp account", $accountId);

        if ($whatsappAccount->is_coexistence) {
            // The number is already registered against the WhatsApp Business app on the phone, so
            // read its status rather than re-registering it.
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $whatsappAccount->access_token,
                ])->get('https://graph.facebook.com/v22.0/' . $whatsappAccount->phone_number_id, [
                    'fields' => 'status,platform_type',
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    $whatsappAccount->phone_number_status = $data['status'] ?? 'CONNECTED';
                    $whatsappAccount->platform_type       = $data['platform_type'] ?? $whatsappAccount->platform_type;
                    $whatsappAccount->save();
                }
            } catch (Exception $ex) {
                return responseManager("whatsapp_error", $ex->getMessage());
            }

            return responseManager("verification_status", "WhatsApp Phone number  status check successfully", "success");
        }

        try {
            $token = $whatsappAccount->access_token;
            $phoneNumberId = $whatsappAccount->phone_number_id;

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ])->post('https://graph.facebook.com/v22.0/' . $phoneNumberId . '/register', [
                'messaging_product' => 'whatsapp',
                'pin' => '123456',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['success']) && $data['success'] == true) {
                    $whatsappAccount->phone_number_status = "CONNECTED";
                    $whatsappAccount->save();
                }
            }
        } catch (Exception $ex) {
            return responseManager("whatsapp_error", $ex->getMessage());
        }

        $message = "WhatsApp Phone number  status check successfully";
        return responseManager("verification_status", $message, "success");
    }

    public function whatsappAccountConnect($id)
    {
        $user                        = getParentUser();
        $whatsappAccount             = WhatsappAccount::where('user_id', $user->id)->findOrFailWithApi("whatsapp account", $id);
        $whatsappAccount->is_default = Status::YES;
        $whatsappAccount->save();

        WhatsappAccount::where('user_id', $user->id)->where('id', '!=', $whatsappAccount->id)->update(['is_default' => Status::NO]);

        $message = "WhatsApp account connected successfully";
        return responseManager("whatsapp_success", $message, "success");
    }

    /**
     * On a coexistence number a human may be answering from the handset, so the welcome
     * message, automation flows and the AI auto-reply would land on top of them. This
     * toggle is only reachable for coexistence accounts; Cloud API accounts are untouched.
     */
    public function coexistenceAutomationToggle($id)
    {
        $user            = getParentUser();
        $whatsappAccount = WhatsappAccount::where('user_id', $user->id)->findOrFailWithApi("whatsapp account", $id);

        if (!$whatsappAccount->is_coexistence) {
            return responseManager("whatsapp_error", "This option is only available for coexistence accounts", "error");
        }

        $whatsappAccount->coexistence_automation = $whatsappAccount->coexistence_automation ? Status::NO : Status::YES;
        $whatsappAccount->save();

        $message = $whatsappAccount->coexistence_automation
            ? "Automation enabled for this account"
            : "Automation disabled for this account";

        return responseManager("whatsapp_success", $message, "success");
    }

    public function whatsappAccountSettingConfirm(Request $request, $accountId)
    {

        $request->validate([
            'meta_access_token' => 'required',
        ]);

        $user = getParentUser();
        $whatsappAccount = WhatsappAccount::where('user_id', $user->id)->findOrFailWithApi("whatsapp account", $accountId);

        try {
            $whatsappData = $this->verifyWhatsappCredentials($whatsappAccount->whatsapp_business_account_id, $request->meta_access_token);
        } catch (Exception $ex) {
            return responseManager("whatsapp_error", $ex->getMessage());
        }

        $whatsappAccount->access_token = $request->meta_access_token;
        $whatsappAccount->code_verification_status = $whatsappData['data']['code_verification_status'];
        $whatsappAccount->save();


        $token = $whatsappAccount->access_token;
        $phoneNumberId = $whatsappAccount->phone_number_id;

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ])->get('https://graph.facebook.com/v22.0/' . $phoneNumberId, [
                'fields' => 'status'
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['status'])) {
                    $whatsappAccount->phone_number_status = $data['status'];
                    $whatsappAccount->save();
                }
            }
        } catch (Exception $ex) {
        }

        $message = "WhatsApp account credentials updated successfully";
        return responseManager("whatsapp_success", $message, "success");
    }

    public function embeddedSignup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_id' => 'required',
            'waba_id' => 'required',
            'phone_number_id' => 'required'
        ]);

        if ($validator->fails()) {
            return apiResponse("error", "validation error", $validator->errors()->all(), [], 422);
        }

        $user = auth()->user();

        if (!featureAccessLimitCheck($user->account_limit)) {
            return apiResponse("error", "error", ["You have reached your account limit"]);
        }

        $accountExists = WhatsappAccount::where('phone_number_id', $request->phone_number_id)
            ->orWhere('whatsapp_business_account_id', $request->waba_id)
            ->exists();

        if ($accountExists) {
            $notify[] = 'This account already has been registered to our system';
            return apiResponse("whatsapp_error", "error", $notify, [
                'success' => false
            ]);
        }

        $userAccounts = WhatsappAccount::where('user_id', $user->id)->get();

        $isDefaultAccount = Status::NO;

        if ($userAccounts->count() < 1) {
            $isDefaultAccount = Status::YES;
        }

        $whatsappAccount = new WhatsappAccount();
        $whatsappAccount->user_id = $user->id;
        $whatsappAccount->whatsapp_business_account_id = $request->waba_id;
        $whatsappAccount->phone_number_id = $request->phone_number_id;
        $whatsappAccount->is_default = $isDefaultAccount;

        $whatsappAccount->save();

        decrementFeature($user, 'account_limit');

        $notify[] = 'WhatsApp account added successfully';
        return apiResponse("success", "success", $notify, [
            'success' => true
        ]);
    }

    /**
     * Onboards a number that stays active in the WhatsApp Business app (Meta "Coexistence").
     *
     * Deliberately separate from embeddedSignup()/accessToken() so the Cloud API path is untouched.
     * The two differences that matter: the phone number is resolved server-side from Meta (a
     * coexistence FINISH payload often omits phone_number_id), and the number is already registered,
     * so there is no POST /{phone_number_id}/register and no PIN step.
     */
    public function coexistenceSignup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'            => 'required|string',
            'waba_id'         => 'required',
            'business_id'     => 'nullable',
            'phone_number_id' => 'nullable',
        ]);

        if ($validator->fails()) {
            return apiResponse("error", "validation error", $validator->errors()->all(), [], 422);
        }

        $user = auth()->user();

        if (!featureAccessLimitCheck($user->account_limit)) {
            return apiResponse("error", "error", ["You have reached your account limit"]);
        }

        try {
            $accessToken = $this->exchangeCodeForToken($request->code);
            $this->subscribeApp($request->waba_id, $accessToken);
            $phoneNumbers = $this->fetchPhoneNumbers($request->waba_id, $accessToken);
        } catch (Exception $ex) {
            return apiResponse("whatsapp_error", "error", [$ex->getMessage()], [
                'success' => false
            ]);
        }

        $phoneNumbers = collect($phoneNumbers);

        // platform_type as reported by Meta is the authoritative discriminator - never trust the
        // client's claim about which onboarding mode it ran.
        $number = $phoneNumbers->firstWhere('platform_type', 'SMB_APP');

        if (!$number && $request->phone_number_id) {
            $number = $phoneNumbers->firstWhere('id', $request->phone_number_id);
        }

        if (!$number) {
            $number = $phoneNumbers->first();
        }

        if (!$number || empty($number['id'])) {
            return apiResponse("whatsapp_error", "error", ['Could not resolve the WhatsApp phone number for this business account.'], [
                'success' => false
            ]);
        }

        $accountExists = WhatsappAccount::where('phone_number_id', $number['id'])
            ->orWhere('whatsapp_business_account_id', $request->waba_id)
            ->exists();

        if ($accountExists) {
            return apiResponse("whatsapp_error", "error", ['This account already has been registered to our system'], [
                'success' => false
            ]);
        }

        $whatsappAccount                               = new WhatsappAccount();
        $whatsappAccount->user_id                      = $user->id;
        $whatsappAccount->whatsapp_business_account_id = $request->waba_id;
        $whatsappAccount->business_id                  = $request->business_id;
        $whatsappAccount->phone_number_id              = $number['id'];
        $whatsappAccount->phone_number                 = $number['display_phone_number'] ?? null;
        $whatsappAccount->business_name                = $number['verified_name'] ?? null;
        $whatsappAccount->code_verification_status     = $number['code_verification_status'] ?? 'NOT_VERIFIED';
        $whatsappAccount->platform_type                = $number['platform_type'] ?? 'SMB_APP';
        $whatsappAccount->access_token                 = $accessToken;
        $whatsappAccount->phone_number_status          = $number['status'] ?? 'CONNECTED';
        $whatsappAccount->is_coexistence               = Status::YES;
        $whatsappAccount->is_default                   = WhatsappAccount::where('user_id', $user->id)->count() ? Status::NO : Status::YES;
        $whatsappAccount->save();

        $appData = $this->metaAppId($request->waba_id, $accessToken);

        if (isset($appData['id'])) {
            $whatsappAccount->meta_app_id = $appData['id'];
            $whatsappAccount->save();
        }

        decrementFeature($user, 'account_limit');

        $notify[] = 'WhatsApp account connected successfully';
        return apiResponse("success", "success", $notify, [
            'success' => true
        ]);
    }

    public function accessToken(Request $request)
    {
        $whatsappAccount = WhatsappAccount::where('user_id', auth()->id())
            ->where('whatsapp_business_account_id', $request->waba_id)
            ->first();

        try {
            $accessToken = $this->exchangeCodeForToken($request->code);
        } catch (Exception $ex) {
            return apiResponse("whatsapp_error", "error", [$ex->getMessage()], [
                'success' => false
            ]);
        }

        if (!$whatsappAccount) {
            return apiResponse("whatsapp_error", "error", ['The WhatsApp account is not found'], [
                'success' => false
            ]);
        }

        $data = ['access_token' => $accessToken];

        $whatsappAccount->access_token = $data['access_token'];

        $this->subscribeApp($whatsappAccount->whatsapp_business_account_id, $data['access_token']);

        $appData = $this->metaAppId($whatsappAccount->whatsapp_business_account_id, $data['access_token']);

        if (isset($appData['id'])) {
            $whatsappAccount->meta_app_id = $appData['id'];
        }

        $whatsappAccount->save();

        $notify[] = 'Access token updated successfully';
        return apiResponse("success", "success", $notify, [
            'success' => true,
            'access_token' => $data['access_token']
        ]);
    }

    /**
     * Exchanges an Embedded Signup code for a long-lived access token.
     *
     * Shared by the Cloud API and coexistence onboarding paths. Unlike the inline version this
     * replaced, a failed exchange raises a readable exception instead of a fatal on a missing key.
     */
    private function exchangeCodeForToken($code)
    {
        $url = "https://graph.facebook.com/v21.0/oauth/access_token";

        $response = Http::get($url, [
            'client_id' => gs('meta_app_id'),
            'client_secret' => gs('meta_app_secret'),
            'code' => $code,
        ]);

        $data = $response->json();

        if (!is_array($data) || empty($data['access_token'])) {
            throw new Exception($data['error']['message'] ?? 'Failed to obtain an access token from Meta.');
        }

        $permanentToken = $this->longLivedToken($data['access_token']);

        return $permanentToken['access_token'] ?? $data['access_token'];
    }

    private function longLivedToken($shortLivedToken)
    {
        $url = "https://graph.facebook.com/v20.0/oauth/access_token";
        $response = Http::get($url, [
            'grant_type' => 'fb_exchange_token',
            'client_id' => gs('meta_app_id'),
            'client_secret' => gs('meta_app_secret'),
            'fb_exchange_token' => $shortLivedToken
        ]);

        return $response->json();
    }

    private function subscribeApp($wabaId, $accessToken)
    {
        $url = "https://graph.facebook.com/v23.0/{$wabaId}/subscribed_apps";

        $response = Http::post($url, [
            'access_token' => $accessToken
        ]);
    }

    private function metaAppId($wabaId, $accessToken)
    {
        $appUrl = "https://graph.facebook.com/v23.0/{$wabaId}?fields=id,name";

        $appResponse = Http::get($appUrl, [
            'access_token' => $accessToken
        ]);

        return $appResponse->json();
    }

    public function whatsappPin(Request $request)
    {
        $whatsappAccount = WhatsappAccount::where('user_id', auth()->id())
            ->where('whatsapp_business_account_id', $request->waba_id)
            ->latest('id')
            ->first();

        // A coexistence number is registered on the handset. Posting /register would fail, or worse
        // deregister the device, so this endpoint is a no-op for them.
        if (!$whatsappAccount || $whatsappAccount->is_coexistence) {
            return to_route('user.whatsapp.account.index');
        }

        $url = "https://graph.facebook.com/v24.0/{$whatsappAccount->phone_number_id}/register";

        $response = Http::post($url, [
            'access_token' => $request->access_token,
            'pin' => $request->pin,
            "messaging_product" => "whatsapp"
        ]);

        return to_route('user.whatsapp.account.verification.check', $whatsappAccount->id);
    }
}
