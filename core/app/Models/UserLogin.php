<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
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
            'created_at' => [
                'name' =>  "login at",
                'callback' => function ($item) {
                    return showDateTime($item->created_at,lang:'en');
                }
            ],
            'user_ip' => [
                'name' => "ip"
            ],
            'location' => [
                'callback' => function ($item) {
                    return @$item->city . ", " . @$item->country;
                }
            ],
            'browser',
            'os'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
