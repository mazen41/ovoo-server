<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function pending()
    {
        $pageTitle = 'Pending Withdrawals';
        $baseQuery = $this->baseQuery("pending");

        if (request()->export) {
            return $this->callExportData($baseQuery);
        }
        $withdrawals = $baseQuery->with(['user', 'method'])->paginate(getPaginate());
        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals'));
    }

    public function approved()
    {
        $pageTitle = 'Approved Withdrawals';
        $baseQuery = $this->baseQuery("approved");

        if (request()->export) {
            return $this->callExportData($baseQuery);
        }
        $withdrawals = $baseQuery->with(['user', 'method'])->paginate(getPaginate());
        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals'));
    }

    public function rejected()
    {
        $pageTitle = 'Rejected Withdrawals';
        $baseQuery = $this->baseQuery("rejected");

        if (request()->export) {
            return $this->callExportData($baseQuery);
        }
        $withdrawals = $baseQuery->with(['user', 'method'])->paginate(getPaginate());

        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals'));
    }

    public function all()
    {
        $baseQuery = $this->baseQuery();

        if (request()->export) {
            return $this->callExportData($baseQuery);
        }

        $withdrawals    = $baseQuery->with(['user', 'method'])->paginate(getPaginate());
        $pageTitle      = 'All Withdrawals';
        $withdrawalData = $this->withdrawalData($baseQuery);
        $widget         = $withdrawalData['summary'];

        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals', 'widget'));
    }

    protected function withdrawalData($baseQuery)
    {
        $successfulSummary = (clone $baseQuery)->where('status', Status::PAYMENT_SUCCESS)->sum('amount');
        $todaySummary      = (clone $baseQuery)->where('status', Status::PAYMENT_PENDING)->whereDate('created_at', now())->sum('amount');
        $pendingSummary    = (clone $baseQuery)->where('status', Status::PAYMENT_PENDING)->sum('amount');
        $rejectedSummary   = (clone $baseQuery)->where('status', Status::PAYMENT_REJECT)->sum('amount');

        return [
            'summary' => [
                'successful'   => $successfulSummary,
                'pending'      => $pendingSummary,
                'rejected'     => $rejectedSummary,
                'today_summary' => $todaySummary
            ]
        ];
    }

    public function details($id)
    {
        $withdrawal = Withdrawal::where('id', $id)->where('status', '!=', Status::PAYMENT_INITIATE)->with(['user', 'method'])->firstOrFail();
        $pageTitle  = 'Withdrawal Details';
        $details    = $withdrawal->withdraw_information ? json_encode($withdrawal->withdraw_information) : null;

        return view('admin.withdraw.detail', compact('pageTitle', 'withdrawal', 'details'));
    }

    public function approve(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $withdraw                 = Withdrawal::where('id', $request->id)->where('status', Status::PAYMENT_PENDING)->with('user')->firstOrFail();
        $withdraw->status         = Status::PAYMENT_SUCCESS;
        $withdraw->admin_feedback = $request->details;
        $withdraw->save();

        notify($withdraw->user, 'WITHDRAW_APPROVE', [
            'method_name'     => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount'   => showAmount($withdraw->final_amount, currencyFormat: false),
            'amount'          => showAmount($withdraw->amount, currencyFormat: false),
            'charge'          => showAmount($withdraw->charge, currencyFormat: false),
            'rate'            => showAmount($withdraw->rate, currencyFormat: false),
            'trx'             => $withdraw->trx,
            'admin_details'   => $request->details
        ]);

        $notify[] = ['success', 'Withdrawal approved successfully'];
        return to_route('admin.withdraw.data.pending')->withNotify($notify);
    }


    public function reject(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $withdraw = Withdrawal::where('id', $request->id)->where('status', Status::PAYMENT_PENDING)->with('user')->firstOrFail();

        $withdraw->status         = Status::PAYMENT_REJECT;
        $withdraw->admin_feedback = $request->details;
        $withdraw->save();

        $user           = $withdraw->user;
        $user->balance += $withdraw->amount;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $withdraw->user_id;
        $transaction->amount       = $withdraw->amount;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->remark       = 'withdraw_reject';
        $transaction->details      = 'Refunded for withdrawal rejection';
        $transaction->trx          = $withdraw->trx;
        $transaction->save();

        notify($user, 'WITHDRAW_REJECT', [
            'method_name'     => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount'   => showAmount($withdraw->final_amount, currencyFormat: false),
            'amount'          => showAmount($withdraw->amount, currencyFormat: false),
            'charge'          => showAmount($withdraw->charge, currencyFormat: false),
            'rate'            => showAmount($withdraw->rate, currencyFormat: false),
            'trx'             => $withdraw->trx,
            'post_balance'    => showAmount($user->balance, currencyFormat: false),
            'admin_details'   => $request->details
        ]);

        $notify[] = ['success', 'Withdrawal rejected successfully'];
        return to_route('admin.withdraw.data.pending')->withNotify($notify);
    }

    private function baseQuery($scope = 'query')
    {
        $withdrawals = Withdrawal::$scope()->where('status', '!=', Status::PAYMENT_INITIATE)->filter(['user_id'])->searchable(['trx', 'user:username'])->dateFilter()->filter(['user_id']);
        $request     = request();

        if ($request->method) {
            $withdrawals = $withdrawals->where('method_id', $request->method);
        }
        return $withdrawals->orderBy('id', getOrderBy());
    }

    private function callExportData($baseQuery)
    {
        return exportData($baseQuery, request()->export, "Withdrawal", "A4 landscape");
    }
}
