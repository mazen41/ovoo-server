<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InteractiveList extends Model
{
    protected $casts = [
        'header'   => 'array',
        'body'     => 'array',
        'footer'   => 'array',
        'sections' => 'array',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class, 'interactive_list_id','id');
    }

}
