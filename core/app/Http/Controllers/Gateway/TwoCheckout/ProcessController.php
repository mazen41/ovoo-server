<?php

namespace App\Http\Controllers\Gateway\TwoCheckout;

use App\Constants\Status;
use App\Models\Deposit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Gateway\PaymentController;

class ProcessController extends Controller
{
    public static function process($deposit)
    {
        $configuration = json_decode($deposit->gatewayCurrency()->gateway_parameter);
        $send['val'] = [
            'sid' => $configuration->merchant_code,
            'mode' => '2CO',
            'li_0_type' => 'product',
            'li_0_name' => $deposit->trx ?? gs('site_name'),
            'li_0_product_id' => "{$deposit->trx}",
            'li_0_price' => round($deposit->final_amount, 2),
            'li_0_quantity' => "1",
            'li_0_tangible' => "N",
            'currency_code' => $deposit->method_currency,
            'demo' => "Y",
        ];

        $send['view'] = 'user.payment.redirect';
        $send['method'] = 'post';
        $send['url'] = 'https://www.2checkout.com/checkout/purchase';
        return json_encode($send);
    }

    public function ipn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'li_0_product_id' => 'required|string',
            'order_number'    => 'required|string',
            'key'             => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Invalid IPN payload'], 400);
        }

        $deposit = Deposit::where('status', Status::PAYMENT_INITIATE)
            ->where('trx', $request->li_0_product_id)
            ->orderBy('id', 'desc')
            ->first();

        if (!$deposit) {
            return response()->json(['message' => 'Deposit not found'], 404);
        }

        $configuration = json_decode($deposit->gatewayCurrency()->gateway_parameter);

        $payload = $configuration->secret_key
            . $configuration->merchant_code
            . $request->order_number
            . round($deposit->final_amount, 2);

        $providedKey = strtoupper((string) $request->key);

        // 2Checkout signs the return key with SHA-256 for accounts that have it enabled, which
        // is what we verify against first. The MD5 form is 2Checkout's legacy scheme and is
        // still accepted so that merchant accounts predating SHA-256 keep working; both are
        // compared with hash_equals so neither leaks timing information.
        $signatureValid = hash_equals(strtoupper(hash('sha256', $payload)), $providedKey)
            || hash_equals(strtoupper(md5($payload)), $providedKey);

        if ($signatureValid) {
            PaymentController::userDataUpdate($deposit);
        }

        return response()->json(['message' => 'ok']);
    }
}
