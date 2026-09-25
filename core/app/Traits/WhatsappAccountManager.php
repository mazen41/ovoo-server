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
        $whatsappAccount->save();

        decrementFeature($user, 'account_limit');

        if (isApiRequest()) {
            $notify[] = "WhatsApp account added successfully";
            return apiResponse("whatsapp_success", "success", $notify, [
                'whatsappAccount' => $whatsappAccount
            ]);
        }

        //connect whatsapp phone number 

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

    public function accessToken(Request $request)
    {
        $whatsappAccount = WhatsappAccount::where('user_id', auth()->id())
            ->where('whatsapp_business_account_id', $request->waba_id)
            ->first();

        $url = "https://graph.facebook.com/v21.0/oauth/access_token";

        $response = Http::get($url, [
            'client_id' => gs('meta_app_id'),
            'client_secret' => gs('meta_app_secret'),
            'code' => $request->code,
        ]);

        $data = $response->json();

        $permanentToken = $this->longLivedToken($data['access_token']);

        if ($permanentToken['access_token']) {
            $data['access_token'] = $permanentToken['access_token'];
        }

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
        $appUrl = "https://graph.facebook.com/v23.0/app?{$wabaId}?fields=name,id";

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

        $url = "https://graph.facebook.com/v24.0/{$whatsappAccount->phone_number_id}/register";

        $response = Http::post($url, [
            'access_token' => $request->access_token,
            'pin' => $request->pin,
            "messaging_product" => "whatsapp"
        ]);

        return to_route('user.whatsapp.account.verification.check', $whatsappAccount->id);
    }
}
