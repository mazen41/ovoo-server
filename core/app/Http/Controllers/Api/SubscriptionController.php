<?php

namespace App\Http\Controllers\Api;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\SubscriptionManager;
use App\Models\PlanPurchase;
use App\Models\PricingPlan;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    public function index()
    {
        $user         = getParentUser();
        $pricingPlans = PricingPlan::active()->orderBy('monthly_price')->get();
        $activePlan   = $user->plan;

        $subscriptions      = PlanPurchase::where('user_id', $user->id)->filter(['payment_method'])->orderBy('id', 'desc')->with('gateway')->paginate(getPaginate());

        $activeSubscription = $subscriptions?->first();

        return apiResponse('plan', 'success', ["All pricing plan"], [
            'pricing_plans' => $pricingPlans,
            'user'          => $user,
            'active_plan'   => $activePlan ? $activePlan->only(['name', 'description']) : [],
            'purchase_data' => [
                'total'           => showAmount($activeSubscription?->amount ?? 0),
                'billing_cycle'   => $activeSubscription?->billing_cycle ?? "N/A",
                'purchase_at'     => showDateTime($activeSubscription?->created_at),
                'active_at'       => showDateTime($activeSubscription?->created_at),
                'next_billing_at' => showDateTime($user?->plan_expired_at),
            ]
        ]);
    }
    public function purchaseHistory()
    {
        $user         = getParentUser();
        $purchaseHistories = PlanPurchase::where('user_id', $user->id)->orderBy('id', 'desc')->with('gateway')->paginate(getPaginate());

        return apiResponse('purchase_history', 'success', ["All purchase histories"], [
            'purchase_histories' => $purchaseHistories,
        ]);
    }

    public function downloadInvoice($subscriptionId)
    {

        $pageTitle    = "Download Invoice";
        $user         = getParentUser();
        $subscription = PlanPurchase::where('user_id', $user->id)->with(['plan', 'user'])->find($subscriptionId);

        if (!$subscription) {
            return apiResponse('subscription_not_found', 'error', ['The subscription is not found']);
        }

        $pdf          = Pdf::loadView(activeTemplate() . "user.whatsapp.print-invoice", compact('subscription', 'pageTitle'));
        $fileName     = 'invoice.pdf';
        return $pdf->stream($fileName);
    }


    public function purchase(Request $request, $planId)
    {
        $request->validate([
            'plan_recurring'          => ['required', Rule::in([Status::MONTHLY, Status::YEARLY])],
            'purchase_payment_option' => ['required', Rule::in([Status::GATEWAY_PAYMENT, Status::WALLET_PAYMENT])],
        ]);

        $user        = auth()->user();
        $pricingPlan = PricingPlan::active()->find($planId);

        if (!$pricingPlan) {
            return apiResponse("plan_not_found", "error", ["The pricing plan is not found"]);
        }

        $purchasePrice  = getPlanPurchasePrice($pricingPlan, $request->plan_recurring);

        if ($purchasePrice <= 0) {

            if (PlanPurchase::where('user_id', $user->id)->where('amount', "<=", $purchasePrice)->count()) {
                return apiResponse("free_plan_limit_exceeded", "error", ["You cannot subscribe to the free plan more than once."]);
            }

            $this->updateUserSubscription($user, $pricingPlan, $request->plan_recurring);
            return apiResponse("plan_purchased", "success", ["Plan purchased successfully."]);
        }

        if ($request->purchase_payment_option == Status::GATEWAY_PAYMENT) {
            return apiResponse("payment_via_gateway", "success", ["Please redirect to new screen to make payment."], [
                'plan'            => $pricingPlan,
                'recurring_type'  => $request->plan_recurring,
                'purchase_amount' => $purchasePrice
            ]);
        }

        if ($user->balance < $purchasePrice) {
            return apiResponse("insufficient_balance", "error", ["Insufficient balance."]);
        }

        $this->updateUserSubscription($user, $pricingPlan, $request->plan_recurring);

        return apiResponse("plan_purchased", "success", ["Plan purchased successfully."]);
    }

    public static function updateUserSubscription($user, $pricingPlan, $recurringType, $method = Status::WALLET_PAYMENT, $methodCode = 0, $coupon = null)
    {
        $purchasePrice = getPlanPurchasePrice($pricingPlan, $recurringType);


        $expireAt = SubscriptionManager::nextExpiry($user, $recurringType);

        SubscriptionManager::recordPurchase(
            user: $user,
            pricingPlan: $pricingPlan,
            recurringType: $recurringType,
            expireAt: $expireAt,
            amount: $purchasePrice,
            paymentMethod: $method,
            methodCode: $methodCode,
            coupon: $coupon
        );

        $amount = getAmount($purchasePrice);

        $user->balance -= $amount;
        SubscriptionManager::applyPlan($user, $pricingPlan, $expireAt);
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
