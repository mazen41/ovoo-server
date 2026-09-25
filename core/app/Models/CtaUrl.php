<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CtaUrl extends Model
{
    protected $casts = [
        'header' => 'array',
        'footer' => 'array',
        'body'   => 'array',
        'action' => 'array',
        'footer' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

}
