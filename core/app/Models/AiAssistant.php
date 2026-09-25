<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class AiAssistant extends Model
{

    use GlobalStatus;

    protected $casts = [
        'config' => 'object',
    ];

}
