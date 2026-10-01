<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class ExternalApiIpWhiteList extends Model
{
    use GlobalStatus;

    protected $casts = [
        'user_id' => 'integer',
        'ip'      => 'string',
        'status'  => 'integer'
    ];

    public function user() { return $this->belongsTo(User::class); }
}
