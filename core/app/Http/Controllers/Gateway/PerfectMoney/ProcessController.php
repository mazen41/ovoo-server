<?php

namespace App\Http\Controllers\Gateway\PerfectMoney;

use App\Constants\Status;
use App\Models\Deposit;
use App\Http\Controllers\Gateway\PaymentController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProcessController extends Controller
{

    /*
     * Perfect Money Gateway
     */
    public static function process($deposit)
    {
        $gateway_currency = $deposit->gatewayCurrency();

        $perfectAcc = json_decode($gateway_currency->gateway_parameter);

        $val['PAYEE_ACCOUNT'] = trim($perfectAcc->wallet_id);
        $val['PAYEE_NAME'] = gs('site_name');
        $val['PAYMENT_ID'] = "$deposit->trx";
        $val['PAYMENT_AMOUNT'] = round($deposit->final_amount,2);
        $val['PAYMENT_UNITS'] = "$deposit->method_currency";

        $val['STATUS_URL'] = route('ipn.'.$deposit->gateway->alias);
        $val['PAYMENT_URL'] = route('home').$deposit->success_url;
        $val['PAYMENT_URL_METHOD'] = 'POST';
        $val['NOPAYMENT_URL'] = route('home').$deposit->failed_url;
        $val['NOPAYMENT_URL_METHOD'] = 'POST';
        $val['SUGGESTED_MEMO'] = auth()->user()->username;
        $val['BAGGAGE_FIELDS'] = 'IDENT';


        $send['val'] = $val;
        $send['view'] = 'user.payment.redirect';
        $send['method'] = 'post';
        $send['url'] = 'https://perfectmoney.is/api/step1.asp';

        return json_encode($send);
    }
    public function ipn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'PAYMENT_ID'        => 'required|string',
            'PAYEE_ACCOUNT'     => 'required|string',
            'PAYMENT_AMOUNT'    => 'required|numeric',
            'PAYMENT_UNITS'     => 'required|string',
            'PAYMENT_BATCH_NUM' => 'required|string',
            'PAYER_ACCOUNT'     => 'required|string',
            'TIMESTAMPGMT'      => 'required|string',
            'V2_HASH'           => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid IPN payload'], 400);
        }

        $deposit = Deposit::where('trx', $request->PAYMENT_ID)->orderBy('id', 'DESC')->first();

        if (!$deposit) {
            return response()->json(['message' => 'Deposit not found'], 404);
        }

        $pmAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);

        // Perfect Money's V2_HASH is specified as MD5, so the algorithm is fixed by the gateway.
        // hash_equals keeps the comparison timing safe and avoids PHP's loose string compare.
        $passphrase = strtoupper(md5($pmAcc->passphrase));

        $string =
            $request->PAYMENT_ID . ':' . $request->PAYEE_ACCOUNT . ':' .
            $request->PAYMENT_AMOUNT . ':' . $request->PAYMENT_UNITS . ':' .
            $request->PAYMENT_BATCH_NUM . ':' .
            $request->PAYER_ACCOUNT . ':' . $passphrase . ':' .
            $request->TIMESTAMPGMT;

        if (!hash_equals(strtoupper(md5($string)), strtoupper((string) $request->V2_HASH))) {
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $amount = $request->PAYMENT_AMOUNT;
        $unit   = $request->PAYMENT_UNITS;

        if ($request->PAYEE_ACCOUNT == $pmAcc->wallet_id && $unit == $deposit->method_currency && $amount == round($deposit->final_amount, 2) && $deposit->status == Status::PAYMENT_INITIATE) {
            // The payload is only recorded once the payment itself has been verified.
            $deposit->detail = $request->all();
            $deposit->save();

            //Update User Data
            PaymentController::userDataUpdate($deposit);
        }

        return response()->json(['message' => 'ok']);
    }
}
