<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    use GlobalStatus;

    protected $guarded = ['id'];

    public function subscriptions()
    {
        return $this->hasMany(PlanPurchase::class, 'plan_id');
    }

    public function popularBadge(): Attribute
    {
        return new Attribute(
            get: fn() => $this->is_popular == Status::YES ? '<span class="badge badge--success">' . trans('Yes') . '</span>' : '<span class="badge badge--danger">' . trans('No') . '</span>',
        );
    }

    public function campaignBadge(): Attribute
    {
        return new Attribute(
            get: fn() => $this->campaign_available == Status::YES ? '<span class="badge badge--success">' . trans('Yes') . '</span>' : '<span class="badge badge--danger">' . trans('No') . '</span>',
        );
    }

  
}
