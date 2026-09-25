<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PricingPlan;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    public function methods()
    {
        $gatewayCurrency = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('method_code')->get();
        $notify[] = 'Payment Methods';

        return apiResponse("deposit_methods", "success", $notify, [
            'methods'    => $gatewayCurrency,
            'image_path' => getFilePath('gateway')
        ]);
    }

    public function depositInsert(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'method_code'    => 'required',
            'currency'       => 'required',
            'amount'         => 'nullable|numeric|gt:0',
            'plan_id'        => 'nullable|integer|exists:pricing_plans,id',
            'plan_recurring' => ['nullable', Rule::in([Status::MONTHLY, Status::YEARLY])],
        ]);

        if ($validator->fails()) {
            return apiResponse("validation_error", "error", $validator->errors()->all());
        }

        $user = getParentUser();

        if ($request->plan_id) {
            $pricingPlan   = PricingPlan::active()->find($request->plan_id);

            if (!$pricingPlan) {
                return apiResponse("plan_not_found", "error", ["The pricing plan is not found"]);
            }

            $amount = getPlanPurchasePrice($pricingPlan, $request->plan_recurring);
        } else {
            $amount = $request->amount;
        }


        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->where('method_code', $request->method_code)->where('currency', $request->currency)->first();

        if (!$gate) {
            $notify[] = 'The payment gateway is not found';
            return apiResponse("invalid_gateway", "error", $notify);
        }

        if ($gate->min_amount > $amount || $gate->max_amount < $amount) {
            $notify[] = 'Please follow deposit limit';
            return apiResponse("cross_limit", "error", $notify);
        }



        $charge      = $gate->fixed_charge + ($amount * $gate->percent_charge / 100);
        $payable     = $amount + $charge;
        $finalAmount = $payable * $gate->rate;

        $data                      = new Deposit();
        $data->from_api            = 1;
        $data->plan_id             = @$pricingPlan->id ?? 0;
        $data->plan_recurring_type = $request->plan_recurring ?? 0;
        $data->user_id             = $user->id;
        $data->method_code         = $gate->method_code;
        $data->method_currency     = strtoupper($gate->currency);
        $data->amount              = $amount;
        $data->charge              = $charge;
        $data->rate                = $gate->rate;
        $data->final_amount        = $finalAmount;
        $data->btc_amount          = 0;
        $data->btc_wallet          = "";
        $data->success_url         = urlPath('user.deposit.history');
        $data->failed_url          = urlPath('user.deposit.history');
        $data->trx                 = getTrx();
        $data->save();

        $notify[] = 'Deposit inserted';

        return apiResponse("deposit_inserted", "success", $notify, [
            'deposit'      => $data,
            'redirect_url' => route('deposit.app.confirm', encrypt($data->id))
        ]);
    }
}
