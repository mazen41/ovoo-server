<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    protected $casts = [
        'withdraw_information' => 'object'
    ];

    protected $hidden = [
        'withdraw_information'
    ];

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
            'method_id' => [
                'name'     => "Gateway",
                "callback" => function ($item) {
                    return $item->method->name;
                }
            ],
            'trx' => [
                'name' => "Transaction",
            ],
            'created_at' => [
                'name'     => "Initiated",
                "callback" => function ($item) {
                    return showDateTime($item->created_at);
                }
            ],
            'amount' => [
                'name'     => "Amount",
                "callback" => function ($item) {
                    return showAmount($item->amount) . "-" . showAmount($item->charge) . "=" . showAmount($item->amount - $item->charge);
                }
            ],
            'Conversion' => [
                'name'     => "Conversion",
                "callback" => function ($item) {
                    return showAmount(1) . "=" . showAmount($item->rate, currencyFormat: false) . " " . $item->currency . " = " . showAmount($item->final_amount, currencyFormat: false) . " " . $item->currency;
                }
            ],
            'status' => [
                "callback" => function ($item) {
                    return strip_tags($item->statusBadge);
                }
            ],
        ];
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function method()
    {
        return $this->belongsTo(WithdrawMethod::class, 'method_id');
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::PAYMENT_PENDING) {
                $html = '<span class="badge badge--warning">' . trans('Pending') . '</span>';
            } elseif ($this->status == Status::PAYMENT_SUCCESS) {
                $html = '<span><span class="badge badge--success">' . trans('Approved') . '</span><br>' . diffForHumans($this->updated_at) . '</span>';
            } elseif ($this->status == Status::PAYMENT_REJECT) {
                $html = '<span><span class="badge badge--danger">' . trans('Rejected') . '</span><br>' . diffForHumans($this->updated_at) . '</span>';
            }
            return $html;
        });
    }

    public function scopePending($query)
    {
        return $query->where('status', Status::PAYMENT_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', Status::PAYMENT_SUCCESS);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', Status::PAYMENT_REJECT);
    }
}
