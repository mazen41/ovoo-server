<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Chatbot extends Model
{
    use GlobalStatus;

    public function whatsapp()
    {
        return $this->belongsTo(WhatsappAccount::class, 'whatsapp_business_account_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::ENABLE) {
                $html = '<span class="badge badge--success">' . trans('Active') . '</span>';
            } else {
                $html = '<span class="badge badge--danger">' . trans('Inactive') . '</span>';
            }
            return $html;
        });
    }


}
