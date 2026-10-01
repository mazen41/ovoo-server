<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CtaUrl extends Model
{
    protected $casts = [
        'header' => 'array',
        'footer' => 'array',
        'body'   => 'array',
        'action' => 'array',
        'footer' => 'array',
    ];

    protected $appends = ['header_image'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function headerImage(): Attribute
    {
        return new Attribute(function () {
            $image = $this->header['image'] ?? null;
            if ($image) {
                return $image['link'];
            }
            return null;
        });
    }

}
