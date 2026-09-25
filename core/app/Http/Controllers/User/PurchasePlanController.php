<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\PlanPurchase;
use App\Models\PricingPlan;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PurchasePlanController extends Controller
{
    public function checkCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon' => 'required',
            'plan_id' => 'required',
        ], [
            'plan_id.required' => 'Please select a plan',
            'coupon.required' => 'Please enter a coupon code',
        ]);

        if ($validator->fails()) {
            return apiResponse("validation_error", "error", $validator->errors()->all(), [
                'success' => false,
            ]);
        }

        $user = auth()->user();
        $plan = PricingPlan::active()->find($request->plan_id);

        if (!$plan) {
            return apiResponse("not_found", "error", ["The selected plan is not found"], [
                'success' => false,
            ]);
        }

        $coupon = Coupon::active()->where('code', $request->coupon)->first();

        if (!$coupon) {
            return apiResponse("coupon_check", "error", ["The coupon code is invalid or does not exist"], [
                'success' => false,
            ]);
        }

        if ($coupon->start_date->isFuture() || $coupon->end_date->isPast()) {
            return apiResponse("coupon_check", "error", [
                "Coupon is not valid for this time period."
            ], [
                'success' => false,
            ]);
        }

        if ($coupon->use_limit != Status::UNLIMITED && $coupon->totalUses()->count() >= $coupon->use_limit) {
            return apiResponse("coupon_check", "error", ["Coupon has been used maximum " . $coupon->use_limit . " times"], [
                'success' => false,
            ]);
        }

        if ($coupon->per_user_limit != Status::UNLIMITED && $coupon->totalUses()->where('user_id', $user->id)->count() >= $coupon->per_user_limit) {
            return apiResponse("coupon_check", "error", ["You have already used this coupon"], [
                'success' => false,
            ]);
        }

        $purchasePrice = getPlanPurchasePrice($plan, $request->recurring_type);

        if ($coupon->min_purchase_amount > $purchasePrice) {
            return apiResponse("coupon_check", "error", ["Minimum purchase amount of " . showAmount($coupon->min_purchase_amount) . " not reached"], [
                'success' => false,
            ]);
        }

        if ($coupon->type == Status::COUPON_TYPE_PERCENTAGE) {
            $discountAmount = $purchasePrice * $coupon->amount / 100;
        } else {
            $discountAmount = $purchasePrice - $coupon->amount;
        }

        return apiResponse("coupon_check", "success", ["Coupon applied successfully"], [
            'success'       => true,
            'coupon'        => $coupon,
            'discount'      => $discountAmount,
            'after_discount' => $purchasePrice - $discountAmount
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pricing_plan_id'         => 'required',
            'plan_recurring'          => ['required', Rule::in([Status::MONTHLY, Status::YEARLY])],
            'purchase_payment_option' => ['required', Rule::in([Status::GATEWAY_PAYMENT, Status::WALLET_PAYMENT])],
        ]);

        $user        = auth()->user();
        $pricingPlan = PricingPlan::active()->find($request->pricing_plan_id);
        $coupon = null;

        if (!$pricingPlan) {
            $notify[] = ['error', 'The pricing plan is not found'];
            return back()->withNotify($notify);
        }

        if ($request->coupon_code) {
            $coupon = Coupon::active()->where('code', $request->coupon_code)->first();
        }

        $purchasePrice  = getPlanPurchasePrice($pricingPlan, $request->plan_recurring);

        if ($coupon) {
            $purchasePrice = applyCouponDiscount($coupon, $purchasePrice);
        }

        if ($purchasePrice <= 0) {
            if (PlanPurchase::where('user_id', $user->id)->where('amount', "<=", $purchasePrice)->count()) {
                $notify[] = ['error', 'You cannot subscribe to the free plan more than once.'];
                return back()->withNotify($notify);
            }

            $this->updateUserSubscription($user, $pricingPlan, $request->plan_recurring);

            $notify[] = ['success', 'Plan purchased successfully.'];
            return to_route('user.subscription.index', ['tab' => 'current-plan'])->withNotify($notify);
        }

        if ($request->purchase_payment_option == Status::GATEWAY_PAYMENT) {
            $pricingPlan->recurring_type = $request->plan_recurring;
            session()->put('pricing_plan', $pricingPlan);
            if ($coupon) {
                session()->put('coupon', $coupon);
            }
            return to_route('user.deposit.index');
        }

        if ($user->balance < $purchasePrice) {
            $notify[] = ['error', 'Insufficient balance.'];
            return back()->withNotify($notify);
        }

        $this->updateUserSubscription($user, $pricingPlan, $request->plan_recurring, coupon: $coupon);

        $notify[] = ['success', 'Plan purchased successfully.'];
        return to_route('user.subscription.index', ['tab' => 'current-plan'])->withNotify($notify);
    }

    public static function updateUserSubscription($user, $pricingPlan, $recurringType, $method = Status::WALLET_PAYMENT, $methodCode = 0, $coupon = null)
    {
        $purchasePrice = getPlanPurchasePrice($pricingPlan, $recurringType);
        if ($coupon) {
            $purchasePrice  = applyCouponDiscount($coupon, $purchasePrice);
        }

        $discountAmount = getPlanPurchasePrice($pricingPlan, $recurringType) - $purchasePrice;

        $now           = $user->plan_expired_at ? Carbon::parse($user->plan_expired_at) : Carbon::now();
        $expireAt      = null;

        if ($recurringType == Status::YEARLY) {
            $expireAt = $now->addYear();
        } else {
            $expireAt = $now->addMonth();
        }

        $purchase                      = new PlanPurchase();
        $purchase->user_id             = $user->id;
        $purchase->coupon_id           = $coupon->id ?? 0;
        $purchase->plan_id             = $pricingPlan->id;
        $purchase->recurring_type      = $recurringType;
        $purchase->amount              = $purchasePrice;
        $purchase->discount_amount     = $discountAmount ?? 0;
        $purchase->payment_method      = $method;
        $purchase->gateway_method_code = $methodCode;
        $purchase->expired_at          = $expireAt;
        $purchase->save();

        $amount = getAmount($purchasePrice);

        $user->balance             -= $amount;
        $user->plan_id              = $pricingPlan->id;
        $user->account_limit        = $pricingPlan->account_limit    == -1 ? -1 : $user->account_limit    + $pricingPlan->account_limit;
        $user->agent_limit          = $pricingPlan->agent_limit      == -1 ? -1 : $user->agent_limit      + $pricingPlan->agent_limit;
        $user->contact_limit        = $pricingPlan->contact_limit    == -1 ? -1 : $user->contact_limit    + $pricingPlan->contact_limit;
        $user->template_limit       = $pricingPlan->template_limit   == -1 ? -1 : $user->template_limit   + $pricingPlan->template_limit;
        $user->flow_limit           = $pricingPlan->flow_limit       == -1 ? -1 : $user->flow_limit    + $pricingPlan->flow_limit;
        $user->campaign_limit       = $pricingPlan->campaign_limit   == -1 ? -1 : $user->campaign_limit   + $pricingPlan->campaign_limit;
        $user->short_link_limit     = $pricingPlan->short_link_limit == -1 ? -1 : $user->short_link_limit + $pricingPlan->short_link_limit;
        $user->floater_limit        = $pricingPlan->floater_limit    == -1 ? -1 : $user->floater_limit    + $pricingPlan->floater_limit;
        $user->welcome_message      = $pricingPlan->welcome_message;
        $user->ai_assistance        = $pricingPlan->ai_assistance;
        $user->interactive_message  = $pricingPlan->interactive_message;
        $user->ecommerce_available  = $pricingPlan->ecommerce_available;
        $user->api_available        = $pricingPlan->api_available;
        $user->plan_expired_at      = $expireAt;
        $user->save();

        // Transaction
        if ($amount > 0) {
            $transaction               = new Transaction();
            $transaction->trx          = getTrx();
            $transaction->user_id      = $user->id;
            $transaction->amount       = $amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge       = 0;
            $transaction->trx_type     = '-';
            $transaction->details      = 'Purchase plan: ' . $pricingPlan->name;
            $transaction->remark       = 'plan_purchase';
            $transaction->save();

            notify($user, "SUBSCRIPTION_PAYMENT", [
                'trx'          => $transaction->trx,
                'plan_name'    => $pricingPlan->name,
                'duration'     => showDateTime($expireAt),
                'amount'       => showAmount($transaction->amount, currencyFormat: false),
                'next_billing' => showDateTime($expireAt, 'd M Y'),
                'post_balance' => showAmount($user->balance, currencyFormat: false),
                'remark'       => $transaction->remark
            ]);

            $userTotalPurchaseCount = PlanPurchase::where('user_id', $user->id)->count();
            if ($user->ref_by && $userTotalPurchaseCount <= 1) {
                userReferralCommission($user, $purchasePrice);
            }
        }
    }
}
