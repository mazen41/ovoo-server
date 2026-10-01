<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use GlobalStatus;

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function duration(): Attribute
    {
        return new Attribute(
            get: fn() => showDateTime($this->start_date, 'd M Y') . ' - ' . showDateTime($this->end_date, 'd M Y'),
        );
    }

    public function durationDays(): Attribute
    {
        return new Attribute(
            get: fn() => $this->start_date && $this->end_date
                ? Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date))
                : null,
        );
    }

    public function totalUses()
    {
        return $this->hasMany(PlanPurchase::class, 'coupon_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', Status::COUPON_ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', Status::COUPON_INACTIVE);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', Status::COUPON_EXPIRED);
    }
}
