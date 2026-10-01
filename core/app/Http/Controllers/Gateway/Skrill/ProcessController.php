<?php

namespace App\Http\Controllers\Gateway\Skrill;

use App\Constants\Status;
use App\Models\Deposit;
use App\Http\Controllers\Gateway\PaymentController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProcessController extends Controller
{

    /*
     * Skrill Gateway
     */
    public static function process($deposit)
    {
        $general = gs();
        $skrillAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);


        $val['pay_to_email'] = trim($skrillAcc->pay_to_email);
        $val['transaction_id'] = "$deposit->trx";

        $val['return_url'] = route('home').$deposit->success_url;
        $val['return_url_text'] = "Return $general->site_name";
        $val['cancel_url'] = route('home').$deposit->failed_url;
        $val['status_url'] = route('ipn.'.$deposit->gateway->alias);
        $val['language'] = 'EN';
        $val['amount'] = round($deposit->final_amount,2);
        $val['currency'] = "$deposit->method_currency";
        $val['detail1_description'] = "$general->site_name";
        $val['detail1_text'] = "Pay To $general->site_name";
        $val['logo_url'] = siteLogo();

        $send['val'] = $val;
        $send['view'] = 'user.payment.redirect';
        $send['method'] = 'post';
        $send['url'] = 'https://www.moneybookers.com/app/payment.pl';
        return json_encode($send);
    }


    public function ipn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transaction_id' => 'required|string',
            'merchant_id'    => 'required|string',
            'mb_amount'      => 'required|numeric',
            'mb_currency'    => 'required|string',
            'status'         => 'required|numeric',
            'md5sig'         => 'required|string',
            'pay_to_email'   => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid IPN payload'], 400);
        }

        $deposit = Deposit::where('trx', $request->transaction_id)->orderBy('id', 'DESC')->first();

        if (!$deposit) {
            return response()->json(['message' => 'Deposit not found'], 404);
        }

        $skrillrAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);

        // Skrill's protocol fixes md5sig as MD5, so the algorithm is not ours to choose.
        // hash_equals keeps the comparison timing safe and avoids PHP's loose string compare.
        $concatFields = $request->merchant_id
            . $request->transaction_id
            . strtoupper(md5($skrillrAcc->secret_key))
            . $request->mb_amount
            . $request->mb_currency
            . $request->status;

        $signatureValid = hash_equals(
            strtoupper(md5($concatFields)),
            strtoupper((string) $request->md5sig)
        );

        $payeeValid = hash_equals(
            (string) $skrillrAcc->pay_to_email,
            (string) $request->pay_to_email
        );

        if ($signatureValid && $payeeValid && $request->status == 2 && $deposit->status == Status::PAYMENT_INITIATE) {
            PaymentController::userDataUpdate($deposit);
        }

        return response()->json(['message' => 'ok']);
    }
}
