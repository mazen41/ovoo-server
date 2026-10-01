<?php
namespace App\Http\Controllers\Gateway\SslCommerz;

use App\Constants\Status;
use App\Models\Deposit;
use App\Http\Controllers\Gateway\PaymentController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Lib\CurlRequest;
use Illuminate\Support\Facades\Validator;
class ProcessController extends Controller{

    public static function process($deposit){
        $parameters = json_decode($deposit->gatewayCurrency()->gateway_parameter);
        $postData                    = array();
        $alias = $deposit->gateway->alias;
        $postData['store_id']        = $parameters->store_id;
        $postData['store_passwd']    = $parameters->store_password;
        $postData['total_amount']    = $deposit->final_amount;
        $postData['currency']        = $deposit->method_currency;
        $postData['tran_id']         = $deposit->trx;
        $postData['success_url']     = route('ipn.'.$alias);
        $postData['fail_url']        = route('home').$deposit->failed_url;
        $postData['cancel_url']      = route('home').$deposit->failed_url;
        $postData['emi_option'] = "0";

        if(auth()->check()){
            $user = auth()->user();
            $postData['cus_name']  = $user->fullname;
            $postData['cus_email'] = $user->email;
            $postData['cus_phone'] = $user->phone;
        }

        $paymentUrl = "https://securepay.sslcommerz.com/gwprocess/v3/api.php";
        // $paymentUrl = "https://sandbox.sslcommerz.com/gwprocess/v3/api.php";
        $response = CurlRequest::curlPostContent($paymentUrl, $postData);
        $response = json_decode($response);

        if(!$response || !@$response->status){
            $send['error'] = true;
            $send['message'] = 'Something went wrong';
            return json_encode($send);
        }

        if($response->status != 'SUCCESS'){
            $send['error'] = true;
            $send['message'] = 'Something went wrong';
            return json_encode($send);
        }
        $send['redirect']     = true;
        $send['redirect_url'] = $response->redirectGatewayURL;
        return json_encode($send);
    }

    public function ipn(Request $request){
        $validator = Validator::make($request->all(), [
            'tran_id' => 'required|string',
            'val_id'  => 'required|string',
            'status'  => 'required|string',
        ]);

        $notify[] = ['error', 'Invalid request'];

        if ($validator->fails()) {
            return to_route('user.deposit.index')->withNotify($notify);
        }

        $deposit = Deposit::where('trx', $request->tran_id)->orderBy('id', 'DESC')->first();

        if (!$deposit) {
            return to_route('user.deposit.index')->withNotify($notify);
        }

        if ($request->status != 'VALID' || $deposit->status != Status::PAYMENT_INITIATE) {
            return redirect($deposit->failed_url)->withNotify($notify);
        }

        $parameters = json_decode($deposit->gatewayCurrency()->gateway_parameter);

        // The previously used verify_sign hash cannot be trusted: verify_key names the fields
        // that go into the hash and arrives in the callback itself, so the caller decides what
        // the signature covers and can leave out the amount and tran_id entirely. SSLCommerz's
        // validation API is authoritative instead, since the answer comes from SSLCommerz over
        // a server-to-server request keyed by val_id rather than from the callback body.
        $validationUrl = 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php?' . http_build_query([
            'val_id'      => $request->val_id,
            'store_id'    => $parameters->store_id,
            'store_passwd'=> $parameters->store_password,
            'format'      => 'json',
        ]);

        $validation = json_decode(CurlRequest::curlContent($validationUrl));

        if (!$validation || !isset($validation->status)) {
            return redirect($deposit->failed_url)->withNotify($notify);
        }

        $paymentSettled  = in_array($validation->status, ['VALID', 'VALIDATED']);
        $matchesDeposit  = isset($validation->tran_id) && hash_equals((string) $deposit->trx, (string) $validation->tran_id);
        $currencyMatches = isset($validation->currency) && $validation->currency == $deposit->method_currency;
        $amountCovered   = isset($validation->amount) && (float) $validation->amount >= round($deposit->final_amount, 2);

        if (!$paymentSettled || !$matchesDeposit || !$currencyMatches || !$amountCovered) {
            return redirect($deposit->failed_url)->withNotify($notify);
        }

        PaymentController::userDataUpdate($deposit);

        $notify = [['success', 'Payment captured successfully']];
        return redirect($deposit->success_url)->withNotify($notify);
    }
}
