<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\WhatsappAccount;
use App\Traits\WhatsappAccountManager;

class WhatsappAccountController extends Controller
{
    use WhatsappAccountManager;

    public function addWhatsappAccount()
    {
        $pageTitle = "Add WhatsApp Account";
        return view('Template::user.whatsapp.add_waba_account', compact('pageTitle'));
    }

    public function whatsappAccountSetting($accountId)
    {
        $whatsappAccount = WhatsappAccount::where('user_id', auth()->id())->findOrFail($accountId);
        $pageTitle       = "WhatsApp Account Setting - " . $whatsappAccount->business_name;
        return view('Template::user.whatsapp.setting_waba_account', compact('pageTitle', 'whatsappAccount'));
    }

}
