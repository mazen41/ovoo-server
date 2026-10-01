<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{

    /**
     * The filed for search.
     *
     * @var array
     */

    /**
     * The filed for filter.
     *
     * @var array
     */


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */

    protected $casts = [
        'detail' => 'object'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['detail'];



    /**
     * specified column for export with column manipulation 
     *
     * @var array
     */
    public function exportColumns(): array
    {
        return  [
            'user_id' => [
                'name'     => "Submitted By",
                'callback' => function ($item) {
                    return $item->user->username;
                }
            ],
            'method_code' => [
                'name'     => "Gateway",
                "callback" => function ($item) {
                    return $item->method_code  ? @$item->gateway->name : trans('Google Pay');
                }
            ],
            'trx' => [
                'name' => "Transaction",
            ],
            'created_at' => [
                'name'     => "Initiated",
                "callback" => function ($item) {
                    return showDateTime($item->created_at, lang: 'en');
                }
            ],
            'amount' => [
                'name'     => "Amount",
                "callback" => function ($item) {
                    return showAmount($item->amount, currencyFormat: false) . "+" . showAmount($item->charge, currencyFormat: false) . "=" . showAmount($item->amount + $item->charge);
                }
            ],
            'conversion' => [
                'name'     => "Conversion",
                "callback" => function ($item) {
                    return showAmount(1) . "=" . showAmount($item->rate, currencyFormat: false) . " " . $item->method_currency . " = " . showAmount($item->final_amount, currencyFormat: false) . " " . $item->method_currency;
                }
            ],
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function gateway()
    {
        return $this->belongsTo(Gateway::class, 'method_code', 'code');
    }

    public function methodName()
    {
        if ($this->method_code < 5000) {
            $methodName = @$this->gatewayCurrency()->name;
        } else {
            $methodName = 'Google Pay';
        }
        return $methodName;
    }

    public function pricingPlan()
    {
        return $this->belongsTo(PricingPlan::class, 'plan_id');
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::PAYMENT_PENDING) {
                $html = '<span class="badge badge--warning">' . trans('Pending') . '</span>';
            } elseif ($this->status == Status::PAYMENT_SUCCESS && $this->method_code >= 1000 && $this->method_code <= 5000) {
                $html = '<span><span class="badge badge--success">' . trans('Approved') . '</span><br><span class="fs-11">' . diffForHumans($this->updated_at) . '</span></span>';
            } elseif ($this->status == Status::PAYMENT_SUCCESS && ($this->method_code < 1000 || $this->method_code >= 5000)) {
                $html = '<span class="badge badge--success">' . trans('Succeed') . '</span>';
            } elseif ($this->status == Status::PAYMENT_REJECT) {
                $html = '<span><span class="badge badge--danger">' . trans('Rejected') . '</span><br>' . diffForHumans($this->updated_at) . '</span>';
            } else {
                $html = '<span class="badge badge--dark">' . trans('Initiated') . '</span>';
            }
            return $html;
        });
    }

    // scope
    public function gatewayCurrency()
    {
        return GatewayCurrency::where('method_code', $this->method_code)->where('currency', $this->method_currency)->first();
    }

    public function baseCurrency()
    {
        return @$this->gateway->crypto == Status::ENABLE ? 'USD' : $this->method_currency;
    }

    public function scopePending($query)
    {
        return $query->where('method_code', '>=', 1000)->where('status', Status::PAYMENT_PENDING);
    }

    public function scopeRejected($query)
    {
        return $query->where('method_code', '>=', 1000)->where('status', Status::PAYMENT_REJECT);
    }

    public function scopeApproved($query)
    {
        return $query->where('method_code', '>=', 1000)->where('method_code', '<', 5000)->where('status', Status::PAYMENT_SUCCESS);
    }

    public function scopeSuccessful($query)
    {
        return $query->where('status', Status::PAYMENT_SUCCESS);
    }

    public function scopeInitiated($query)
    {
        return $query->where('status', Status::PAYMENT_INITIATE);
    }
}
