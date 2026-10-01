<?php

namespace App\Models;

use App\Traits\ApiQuery;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    use ApiQuery;

    public function exportColumns(): array
    {
        return  [
            'user_id' => [
                'name' => "User",
                'callback' => function ($item) {
                    return (clone @$item)->user->username;
                }
            ],
            'created_at' => [
                'name' =>  "Sent",
                'callback' => function ($item) {
                    return showDateTime($item->created_at,lang:'en');
                }
            ],
            'sender',
            'subject'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
