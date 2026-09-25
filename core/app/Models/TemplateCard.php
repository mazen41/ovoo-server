<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateCard extends Model
{
    protected $casts = [
        'header' => 'array',
        'buttons' => 'array',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

}
