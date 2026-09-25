<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserApiCredentials extends Model
{
    protected $casts = [
        'user_id'       => 'integer',
        'client_id'     => 'string',
        'client_secret' => 'string'
    ];

    public function user() { return $this->hasOne(User::class); }
}