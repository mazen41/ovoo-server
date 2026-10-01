<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\WhatsappManager;

class WhatsappController extends Controller
{
    use WhatsappManager;

    public function whatsappWebhook()
    {
        $pageTitle = "Setup WhatsApp Webhook";
        return view('Template::user.whatsapp.webhook', compact('pageTitle'));
    }
}
