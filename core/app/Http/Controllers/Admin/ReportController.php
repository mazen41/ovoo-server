<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use App\Models\Transaction;
use App\Models\UserLogin;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function transaction(Request $request)
    {
        $pageTitle = 'Transaction Logs';
        $baseQuery = Transaction::searchable(['trx', 'user:username'])->filter(['trx_type', 'remark', 'user_id'])->dateFilter()->orderBy('id', getOrderBy());

        if (request()->export) {
            return exportData($baseQuery, request()->export, "Transaction");
        }
        
        $transactions = $baseQuery->with('user')->paginate(getPaginate());
        return view('admin.reports.transactions', compact('pageTitle', 'transactions'));
    }

    public function loginHistory(Request $request)
    {
        $pageTitle = 'User Login History';
        $baseQuery = UserLogin::orderBy('id', getOrderBy())->searchable(['user:username'])->filter(['user_id'])->dateFilter();

        if (request()->export) {
            return exportData($baseQuery, request()->export, "UserLogin");
        }

        $loginLogs = $baseQuery->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs'));
    }

    public function loginIpHistory($ip)
    {
        $pageTitle = 'Login by - ' . $ip;
        $baseQuery = UserLogin::where('user_ip', $ip)->orderBy('id', 'desc');

        if (request()->export) {
            return exportData($baseQuery, request()->export, "UserLogin");
        }

        $loginLogs = $baseQuery->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs', 'ip'));
    }

    public function notificationHistory(Request $request)
    {
        $pageTitle = 'Notification History';
        $baseQuery = NotificationLog::orderBy('id', 'desc')->searchable(['user:username'])->filter(['user_id'])->dateFilter();
        if (request()->export) {
            return exportData($baseQuery, request()->export, "NotificationLog");
        }
        $logs = $baseQuery->with('user')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs'));
    }

    public function emailDetails($id)
    {
        $pageTitle = 'Email Details';
        $email     = NotificationLog::findOrFail($id);
        return view('admin.reports.email_details', compact('pageTitle', 'email'));
    }
}
