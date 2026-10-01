<?php

namespace App\Models;

use App\Traits\ApiQuery;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class WhatsappAccount extends Model
{
    use ApiQuery;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function templates()
    {
        return $this->hasMany(Template::class, 'whatsapp_account_id');
    }

    public function welcomeMessage()
    {
        return $this->hasOne(WelcomeMessage::class);
    }

    /**
     * Coexistence numbers stay usable in the WhatsApp Business app on the owner's phone.
     * The column defaults to 0, so every pre-existing account renders as Cloud API.
     */
    public function accountModeBadge(): Attribute
    {
        return new Attribute(function () {
            if ($this->is_coexistence) {
                return '<span class="badge custom--badge badge--info" data-bs-toggle="tooltip" title="' . trans('This number is also active in the WhatsApp Business app on a phone. Messages sent from the phone appear in your inbox.') . '">' . trans('Coexistence') . '</span>';
            }

            return '<span class="badge custom--badge badge--primary">' . trans('Cloud API') . '</span>';
        });
    }

    public function verificationStatusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->code_verification_status == 'VERIFIED') {
                $html = '<span class="badge custom--badge badge--success">' . trans('Verified') . '</span>';
            } elseif ($this->code_verification_status == 'EXPIRED') {
                $html = '<span class="badge custom--badge badge--warning">' . trans('Expired') . '</span>';
            } else {
                $html = '<span class="badge custom--badge badge--danger" data-bs-toggle="tooltip" data-bs-html="true" title="' . trans('If you are using a test account, this status will never be verified. If you want a verified status, you must use a live/production Meta App.') . '">' . trans('Not Verified') . '</span>';
            }
            return $html;
        });
    }
}
