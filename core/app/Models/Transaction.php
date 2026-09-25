<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{


    public function exportColumns(): array
    {
        return  [
            'user_id' => [
                'name' => "User",
                'callback' => function ($item) {
                    return (clone @$item)->user->username;
                }
            ],
            'trx',
            'created_at' => [
                'name' =>  "transacted",
                'callback' => function ($item) {
                    return showDateTime($item->created_at,lang:'en');
                }
            ],
            'amount' => [
                'callback' => function ($item) {
                    return showAmount($item->amount);
                }
            ],
            'post_balance' => [
                'callback' => function ($item) {
                    return showAmount($item->post_balance);
                }
            ],
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
