<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AiAssistant;
use App\Models\WelcomeMessage;
use App\Models\WhatsappAccount;
use Illuminate\Http\Request;

class AutomationController extends Controller
{
    public function welcomeMessage()
    {
        $pageTitle       = "Welcome Message";
        $user            = getParentUser();
        $accounts        = WhatsappAccount::where('user_id', $user->id)->get();
        $availableAccounts = WhatsappAccount::where('user_id', $user->id)->whereDoesntHave('welcomeMessage')->get();
        $welcomeMessages = WelcomeMessage::whereHas('whatsappAccount', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with('whatsappAccount')->get();

        if ($accounts->isEmpty()) {
            $view   = 'Template::user.inbox.whatsapp_account_empty';
        } else {
            $view = 'Template::user.automation.welcome_message';
        }

        return responseManager("welcome_message", $pageTitle, "success", [
            'pageTitle'       => $pageTitle,
            'accounts'        => $availableAccounts,
            'welcomeMessages' => $welcomeMessages,
            'view'            => $view,
        ]);
    }

    public function welcomeMessageStore(Request $request, $id = 0)
    {
        $isRequired = $id ? 'nullable' : 'required';
        $request->validate([
            'whatsapp_account_id' => $isRequired,
            'message'             => 'required|string',
        ]);

        $user = getParentUser();
        if ($id) {
            $welcomeMessage = WelcomeMessage::whereHas('whatsappAccount', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->findOrFail($id);
            $message = "Welcome message updated successfully.";
        } else {

            if (!featureAccessLimitCheck($user->welcome_message)) {
                return responseManager('not_available', "The Welcome Message feature is not included in your current plan. Please upgrade to access this feature");
            }
            $whatsappAccount = WhatsappAccount::where('user_id', $user->id)->whereDoesntHave('welcomeMessage')->find($request->whatsapp_account_id);

            if (!$whatsappAccount) {
                $notify[] = ['error', 'The whatsapp account is invalid'];
                return back()->withNotify($notify);
            }
            if ($whatsappAccount->welcomeMessage) {
                $notify[] = ['error', 'The welcome message already exists for this account'];
                return back()->withNotify($notify);
            }
            $welcomeMessage                      = new WelcomeMessage();
            $welcomeMessage->whatsapp_account_id = $whatsappAccount->id;
            $message                             = "Welcome message created successfully.";
        }

        $welcomeMessage->message = $request->message;
        $welcomeMessage->save();

        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }

    public function welcomeMessageStatus($id)
    {
        $user           = getParentUser();
        $welcomeMessage = WelcomeMessage::whereHas('whatsappAccount', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->findOrFail($id);

        $welcomeMessage->status = !$welcomeMessage->status;
        $welcomeMessage->save();

        $notify[] = ['success', "Welcome message status changed successfully"];
        return back()->withNotify($notify);
    }

    public function aiAssistant()
    {
        $pageTitle = "AI Assistant";

        $activeAiAssistant = AiAssistant::active()->first();

        $user  =  getParentUser();

        $aiSetting = $user->aiSetting;

        if (!$aiSetting) {
            $aiSetting = createAiSetting($user);
        }

        return view('Template::user.automation.ai_assistant', compact('pageTitle', 'aiSetting', 'activeAiAssistant'));
    }

    public function aiAssistantStore(Request $request)
    {

        $check = AiAssistant::active()->exists();

        if (!$check) {
            $notify[] = ['error', ' AI Assistant is not active for this platform.'];
            return back()->withNotify($notify);
        }

        $user = getParentUser();
        
        if(!featureAccessLimitCheck($user->ai_assistance)) {
            return responseManager('not_available', 'Your current plan does not support AI Assistant. Please upgrade your plan.');
        }

        $request->validate([
            'max_length'        => 'required|integer|gte:0',
            'system_prompt'     => 'required|string',
            'fallback_response' => 'nullable|string',
        ]);


        if (!$user->aiSetting) {
            createAiSetting($user);
        }

        $aiSetting                    = $user->aiSetting;
        $aiSetting->max_length        = $request->max_length;
        $aiSetting->system_prompt     = $request->system_prompt;
        $aiSetting->fallback_response = $request->fallback_response;
        $aiSetting->status            = $request->status ? Status::ENABLE : Status::DISABLE;
        $aiSetting->save();

        $notify[] = ['success', 'AI Assistant settings updated successfully'];
        return back()->withNotify($notify);
    }
}
