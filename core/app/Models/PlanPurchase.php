<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class PlanPurchase extends Model
{
    use GlobalStatus;

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function plan()
    {
        return $this->belongsTo(PricingPlan::class, 'plan_id');
    }

    public function billingCycle(): Attribute
    {
        return new Attribute(
            get: fn() => $this->recurring_type == Status::MONTHLY ? 'Monthly' : 'Yearly',
        );
    }

    public function scopeExpired($query)
    {
        return $query->where('status', Status::DISABLE);
    }

    public function gateway()
    {
        return $this->belongsTo(Gateway::class, 'gateway_method_code', 'code');
    }

    public function getPaymentMethod(): Attribute
    {
        return new Attribute(
            get: function () {
                return  $this->payment_method == Status::WALLET_PAYMENT ? 'Wallet' : 'Gateway' . (@$this->gateway ? (" | " . $this->gateway->name) : '');
            }
        );
    }
}
