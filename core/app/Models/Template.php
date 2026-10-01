<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{

    protected $casts = [
        'header'  => 'array',
        'body'    => 'array',
        'buttons' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(TemplateCategory::class, 'category_id');
    }

    public function whatsappAccount()
    {
        return $this->belongsTo(WhatsappAccount::class, 'whatsapp_account_id');
    }

    public function language()
    {
        return $this->belongsTo(TemplateLanguage::class, 'language_id');
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function cards()
    {
        return $this->hasMany(TemplateCard::class, 'template_id', 'id');
    }

    public function verificationStatus()
    {
        $html = '';

        if ($this->status == Status::TEMPLATE_PENDING) {
            $html = '<span class="custom--badge badge--primary">' . trans('Pending') . '</span>';
        } elseif ($this->status == Status::TEMPLATE_APPROVED) {
            $html = '<span class="custom--badge badge--success">' . trans('Approved') . '</span>';
        } elseif ($this->status == Status::TEMPLATE_REJECTED) {
            $html = '<span class="custom--badge badge--danger" data-bs-toggle="tooltip" title="' . __($this->rejected_reason ?? 'Unspecified') . '">' . trans('Rejected') . '</span>';
        } elseif ($this->status == Status::TEMPLATE_DISABLED) {
            $html = '<span class="custom--badge badge--warning">' . trans('Disabled') . '</span>';
        }
        return $html;
    }

    // scopes
    public function scopeApproved($query)
    {
        return $query->where('status', Status::TEMPLATE_APPROVED);
    }

    public function scopePending($query)
    {
        return $query->where('status', Status::TEMPLATE_PENDING);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', Status::TEMPLATE_REJECTED);
    }

    public function scopeDisabled($query)
    {
        return $query->where('status', Status::TEMPLATE_DISABLED);
    }
}
