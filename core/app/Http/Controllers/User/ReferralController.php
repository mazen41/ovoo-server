<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;

class ReferralController extends Controller
{
    public function index()
    {
        $user      = auth()->user();
        $pageTitle = "Referral History";
        $referrals = User::where('ref_by', $user->id)->orderBy('id', 'desc')->paginate(getPaginate());

        $widget['total_referrals']      = $referrals->count();
        $widget['successful_referrals'] = User::where('ref_by', $user->id)->whereHas('transactions')->count();
        $widget['total_earning']        = Transaction::where('user_id', $user->id)->where('remark', 'referral_commission')->sum('amount');

    return view('Template::user.referral.history', compact('pageTitle', 'referrals', 'widget'));
    }
}
