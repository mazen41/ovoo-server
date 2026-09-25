<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class SystemController extends Controller
{
    public function systemInfo()
    {
        $laravelVersion = app()->version();
        $timeZone       = config('app.timezone');
        $pageTitle      = 'Application Information';
        $serverDetails  = $_SERVER;
        $systemDetails  = systemDetails();
        return view('admin.system.info', compact('pageTitle', 'laravelVersion', 'timeZone', 'serverDetails', 'systemDetails'));
    }

    public function optimizeClear()
    {
        Artisan::call('optimize:clear');
        $notify[] = ['success', 'Cache cleared successfully'];
        return back()->withNotify($notify);
    }
}
