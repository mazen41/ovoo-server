<?php

namespace App\Lib;

use App\Constants\Status;
use App\Models\PlanPurchase;
use Carbon\Carbon;

/**
 * Shared subscription logic.
 *
 * Granting a plan happens from three places: a wallet/gateway purchase by the user, the same
 * purchase through the API, and an admin assigning a plan by hand. Each used to carry its own
 * copy of this code and they drifted apart, which is how admin assignment ended up granting the
 * entitlements without ever writing the plan_purchases row the dashboard reads. Keeping the
 * pieces here means a plan granted by an admin looks exactly like one that was bought.
 */
class SubscriptionManager
{
    /**
     * Work out when a newly granted plan should expire.
     *
     * A plan bought while the current one is still running stacks on top of it. Once the old
     * plan has lapsed the new term starts from today instead, otherwise renewing after a long
     * gap would hand back a date that is still in the past.
     */
    public static function nextExpiry($user, $recurringType): Carbon
    {
        $startFrom = $user->plan_expired_at ? Carbon::parse($user->plan_expired_at) : Carbon::now();

        if ($startFrom->isPast()) {
            $startFrom = Carbon::now();
        }

        return $recurringType == Status::YEARLY
            ? $startFrom->addYear()
            : $startFrom->addMonth();
    }

    /**
     * Copy a plan's entitlements onto the user.
     *
     * Limits accumulate so that stacking plans adds allowance, except for -1, which means
     * unlimited and therefore absorbs whatever was there before. The caller is responsible for
     * saving; purchases need to adjust the balance on the same model first.
     */
    public static function applyPlan($user, $pricingPlan, Carbon $expireAt): void
    {
        $user->plan_id          = $pricingPlan->id;
        $user->account_limit    = self::stackLimit($user->account_limit, $pricingPlan->account_limit);
        $user->agent_limit      = self::stackLimit($user->agent_limit, $pricingPlan->agent_limit);
        $user->contact_limit    = self::stackLimit($user->contact_limit, $pricingPlan->contact_limit);
        $user->template_limit   = self::stackLimit($user->template_limit, $pricingPlan->template_limit);
        $user->flow_limit       = self::stackLimit($user->flow_limit, $pricingPlan->flow_limit);
        $user->campaign_limit   = self::stackLimit($user->campaign_limit, $pricingPlan->campaign_limit);
        $user->short_link_limit = self::stackLimit($user->short_link_limit, $pricingPlan->short_link_limit);
        $user->floater_limit    = self::stackLimit($user->floater_limit, $pricingPlan->floater_limit);

        if (addonIsInstalled('tele-wpp')) {
            $user->telegram_bot_limit = self::stackLimit($user->telegram_bot_limit, $pricingPlan->telegram_bot_limit);
        }

        $user->welcome_message     = $pricingPlan->welcome_message;
        $user->ai_assistance       = $pricingPlan->ai_assistance;
        $user->interactive_message = $pricingPlan->interactive_message;
        $user->ecommerce_available = $pricingPlan->ecommerce_available;
        $user->api_available       = $pricingPlan->api_available;
        $user->plan_expired_at     = $expireAt;
    }

    /**
     * Write the plan_purchases row. This is the record the user dashboard, the API and the
     * invoice all read, so nothing may grant a plan without creating one.
     */
    public static function recordPurchase(
        $user,
        $pricingPlan,
        $recurringType,
        Carbon $expireAt,
        $amount,
        int $paymentMethod,
        $methodCode = 0,
        $coupon = null,
        $discountAmount = 0
    ): PlanPurchase {
        $purchase                      = new PlanPurchase();
        $purchase->user_id             = $user->id;
        $purchase->coupon_id           = $coupon->id ?? 0;
        $purchase->plan_id             = $pricingPlan->id;
        $purchase->recurring_type      = $recurringType;
        $purchase->amount              = $amount;
        $purchase->discount_amount     = $discountAmount ?? 0;
        $purchase->payment_method      = $paymentMethod;
        $purchase->gateway_method_code = $methodCode;
        $purchase->expired_at          = $expireAt;
        $purchase->save();

        return $purchase;
    }

    private static function stackLimit($current, $fromPlan)
    {
        return $fromPlan == -1 ? -1 : $current + $fromPlan;
    }
}
