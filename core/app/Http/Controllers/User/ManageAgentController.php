<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\AgentManager;

class ManageAgentController extends Controller
{
    use AgentManager;

    public function create()
    {
        $pageTitle     = "Add Agent";
        $info          = json_decode(json_encode(getIpInfo()), true);
        $mobileCode    = @implode(',', $info['code']);
        $countries     = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view("Template::user.agent.create", compact('pageTitle', 'countries', 'mobileCode'));
    }
}
