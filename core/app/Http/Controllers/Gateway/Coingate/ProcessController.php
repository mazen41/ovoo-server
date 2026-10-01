<?php

namespace App\Http\Controllers\Gateway\Coingate;

use App\Constants\Status;
use App\Models\Deposit;
use App\Http\Controllers\Controller;
use CoinGate\Client;
use CoinGate\Merchant\Order;
use App\Http\Controllers\Gateway\PaymentController;
use App\Lib\CurlRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProcessController extends Controller
{
    /*
     * Coingate Gateway 505
     */

    public static function process($deposit)
    {
        $coingateAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);

        $client = new Client();
        $client->setApiKey($coingateAcc->api_key);
        $client->setEnvironment('live');

        $postParams = array(
            'order_id' => $deposit->trx,
            'price_amount' => round($deposit->final_amount,2),
            'price_currency' => $deposit->method_currency,
            'receive_currency' => $deposit->method_currency,
            'callback_url' => route('ipn.'.$deposit->gateway->alias),
            'cancel_url' => route('home').$deposit->failed_url,
            'success_url' => route('home').$deposit->success_url,
            'title' => 'Payment to ' . gs('site_name'),
            'token' => $deposit->trx
        );

        try {
            $order = $client->order->create($postParams);
        } catch (\Exception $e) {
            $send['error'] = true;
            $send['message'] = $e->getMessage();
            return json_encode($send);
        }
        if ($order) {
            $send['redirect'] = true;
            $send['redirect_url'] = $order->payment_url;
        } else {
            $send['error'] = true;
            $send['message'] = 'Unexpected Error! Please Try Again';
        }
        $send['view'] = '';
        return json_encode($send);
    }

    public function ipn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token'        => 'required|string',
            'status'       => 'required|string',
            'price_amount' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid IPN payload'], 400);
        }

        $ip       = $request->ip();
        $url      = 'https://api.coingate.com/v2/ips-v4';
        $response = CurlRequest::curlContent($url);

        // The endpoint returns one IP per line. A substring search would let an address that
        // merely appears inside a listed one (1.2.3.4 within 11.2.3.45) pass, so match exactly.
        $allowedIps = array_filter(array_map('trim', preg_split('/\R/', (string) $response)));

        if (!in_array($ip, $allowedIps, true)) {
            return response()->json(['message' => 'Unrecognised caller'], 403);
        }

        $deposit = Deposit::where('trx', $request->token)->orderBy('id', 'DESC')->first();

        if (!$deposit) {
            return response()->json(['message' => 'Deposit not found'], 404);
        }

        if ($request->status == 'paid' && $request->price_amount == $deposit->final_amount && $deposit->status == Status::PAYMENT_INITIATE) {
            PaymentController::userDataUpdate($deposit);
        }

        return response()->json(['message' => 'ok']);
    }
}
