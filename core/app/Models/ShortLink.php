<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ShortLink extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mobileNumber(): Attribute
    {
        return new Attribute(
            get: fn() => $this->dial_code . $this->mobile,
        );
    }
}
