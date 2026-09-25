<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function pending()
    {
        $pageTitle = 'Pending Deposits';
        $baseQuery = $this->baseQuery('pending');

        if (request()->export) {
            return $this->callExportData($baseQuery);
        }
        $deposits    = $baseQuery->with(['user', 'gateway'])->paginate(getPaginate());
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }
    public function approved()
    {
        $pageTitle = 'Approved Deposits';
        $baseQuery = $this->baseQuery('approved');
        if (request()->export) {
            return $this->callExportData($baseQuery);
        }

        $deposits    = $baseQuery->with(['user', 'gateway'])->paginate(getPaginate());
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function successful()
    {
        $pageTitle = 'Successful Deposits';
        $baseQuery = $this->baseQuery('successful');

        if (request()->export) {
            return $this->callExportData($baseQuery);
        }
        $deposits    = $baseQuery->with(['user', 'gateway'])->paginate(getPaginate());
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function rejected()
    {
        $pageTitle = 'Rejected Deposits';
        $baseQuery = $this->baseQuery('rejected');

        if (request()->export) {
            return $this->callExportData($baseQuery);
        }
        $deposits    = $baseQuery->with(['user', 'gateway'])->paginate(getPaginate());
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function initiated()
    {
        $pageTitle = 'Initiated Deposits';
        $baseQuery = $this->baseQuery('initiated');

        if (request()->export) {
            return $this->callExportData($baseQuery);
        }
        $deposits    = $baseQuery->with(['user', 'gateway'])->paginate(getPaginate());
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function deposit()
    {
        $pageTitle = 'Deposit History';
        $baseQuery = $this->baseQuery();

        if (request()->export) {
            return $this->callExportData($baseQuery);
        }

        $depositData = $this->summery($baseQuery);
        $deposits    = $baseQuery->with(['user', 'gateway'])->paginate(getPaginate());
        $widget      = $depositData['summary'];

        return view('admin.deposit.log', compact('pageTitle', 'deposits', 'widget'));
    }

    protected function summery($baseQuery)
    {

        $successfulSummary = (clone $baseQuery)->where('status', Status::PAYMENT_SUCCESS)->sum('amount');
        $pendingSummary    = (clone $baseQuery)->where('status', Status::PAYMENT_PENDING)->sum('amount');
        $rejectedSummary   = (clone $baseQuery)->where('status', Status::PAYMENT_REJECT)->sum('amount');
        $initiatedSummary  = (clone $baseQuery)->where('status', Status::PAYMENT_INITIATE)->sum('amount');

        return [
            'summary' => [
                'successful' => $successfulSummary,
                'pending'    => $pendingSummary,
                'rejected'   => $rejectedSummary,
                'initiated'  => $initiatedSummary,
            ]
        ];
    }

    public function details($id)
    {
        $deposit   = Deposit::where('id', $id)->with(['user', 'gateway'])->firstOrFail();
        $pageTitle = $deposit->user->username . ' requested ' . showAmount($deposit->amount);
        $details   = ($deposit->detail != null) ? json_encode($deposit->detail) : null;
        return view('admin.deposit.detail', compact('pageTitle', 'deposit', 'details'));
    }


    public function approve($id)
    {
        $deposit = Deposit::where('id', $id)->where('status', Status::PAYMENT_PENDING)->firstOrFail();

        PaymentController::userDataUpdate($deposit, true);

        $notify[] = ['success', 'Deposit request approved successfully'];

        return to_route('admin.deposit.pending')->withNotify($notify);
    }

    public function reject(Request $request)
    {
        $request->validate([
            'id'      => 'required|integer',
            'message' => 'required|string|max:255'
        ]);
        $deposit = Deposit::where('id', $request->id)->where('status', Status::PAYMENT_PENDING)->firstOrFail();

        $deposit->admin_feedback = $request->message;
        $deposit->status         = Status::PAYMENT_REJECT;
        $deposit->save();

        notify($deposit->user, 'DEPOSIT_REJECT', [
            'method_name'       => $deposit->methodName(),
            'method_currency'   => $deposit->method_currency,
            'method_amount'     => showAmount($deposit->final_amount, currencyFormat: false),
            'amount'            => showAmount($deposit->amount, currencyFormat: false),
            'charge'            => showAmount($deposit->charge, currencyFormat: false),
            'rate'              => showAmount($deposit->rate, currencyFormat: false),
            'trx'               => $deposit->trx,
            'rejection_message' => $request->message
        ]);

        $notify[] = ['success', 'Deposit request rejected successfully'];
        return  to_route('admin.deposit.pending')->withNotify($notify);
    }

    private function baseQuery($scope = 'query')
    {
        $baseQuery = Deposit::$scope()->searchable(['trx', 'user:username'])->filter(['user_id'])->dateFilter();
        $request   = request();

        if ($request->method) {
            if ($request->method != Status::GOOGLE_PAY) {
                $method    = Gateway::where('alias', $request->method)->firstOrFail();
                $baseQuery = $baseQuery->where('method_code', $method->code);
            } else {
                $baseQuery = $baseQuery->where('method_code', Status::GOOGLE_PAY);
            }
        }
        return $baseQuery->orderBy('id', getOrderBy());
    }

    private function callExportData($baseQuery)
    {
        return exportData($baseQuery, request()->export, "deposit", "A4 landscape");
    }
}
