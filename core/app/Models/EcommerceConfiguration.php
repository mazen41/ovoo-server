<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;

class EcommerceConfiguration extends Model
{
    protected $casts = [
        "config"   => "object",
        "user_id"  => "integer",
        "status"   => "integer",
        "provider" => "integer"
    ];

}