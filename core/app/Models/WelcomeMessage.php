<?php

namespace App\Models;

use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;

class WelcomeMessage extends Model
{
    use GlobalStatus;

    public function whatsappAccount()
    {
        return $this->belongsTo(WhatsappAccount::class);
    }
}
