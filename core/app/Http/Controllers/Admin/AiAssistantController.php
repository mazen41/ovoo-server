<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\AiAssistant;
use Illuminate\Http\Request;

class AiAssistantController extends Controller
{
    public function index()
    {
        $pageTitle    = "AI Assistant";
        $aiAssistants = AiAssistant::get();
        return view('admin.ai_assistant.index', compact('pageTitle', 'aiAssistants'));
    }

    public function status($id)
    {
        $aiAssistant = AiAssistant::findOrFail($id);
        $aiAssistant->status = !$aiAssistant->status;
        $aiAssistant->save();

        if ($aiAssistant->status) {
            AiAssistant::whereNot('id', $id)->update(['status' => Status::NO]);
        }

        $notify[] = ['success', 'Status updated successfully'];
        return back()->withNotify($notify);
    }

    public function configure(Request $request, $id)
    {
        $provider = AiAssistant::findOrFail($id);

        $config  = [];

        foreach ($provider->config as $key => $value) {
            $config[$key] = $request->$key;
        }

        $provider->config = $config;
        $provider->save();

        $notify[] = ['success', 'Configuration updated successfully'];
        return back()->withNotify($notify);
    }
}
