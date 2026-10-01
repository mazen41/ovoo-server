<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $casts = [
        'template_header_params' => 'array',
        'template_body_params'   => 'array',
        'send_at'                => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function whatsappAccount()
    {
        return $this->belongsTo(WhatsappAccount::class, 'whatsapp_account_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'campaign_contacts')->withTimestamps();
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::CAMPAIGN_RUNNING) {
                $html = '<span class="custom--badge badge badge--primary">Running</span>';
            } elseif ($this->status == Status::CAMPAIGN_SCHEDULED) {
                $html = '<span class="custom--badge badge badge--warning">Scheduled</span>';
            } elseif ($this->status == Status::CAMPAIGN_COMPLETED) {

                $html = '<span class="custom--badge badge--success">Completed</span>';
            } else {
                $html = '<span class="custom--badge badge badge--danger">Failed</span>';
            }

            return $html;
        });
    }

    /**
     * specified column for export with column manipulation 
     *
     * @var array
     */
    public function exportColumns(): array
    {
        return  [
            'title',
            'template_id' => [
                'name' => "Template",
                "callback" => function ($item) {
                    return $item->template->name;
                }
            ],
            'send_at' => [
                'name' => "Send At",
                "callback" => function ($item) {
                    return showDateTime($item->send_at, lang: 'en');
                }
            ],
            'total_message',
            'total_send',
            'total_success',
            'total_failed',
            'status' => [
                'name' => "Status",
                "callback" => function ($item) {
                    return strip_tags($item->statusBadge);
                }
            ],
            'created_at' => [
                'name'     => "Initiated",
                "callback" => function ($item) {
                    return showDateTime($item->created_at, lang: 'en');
                }
            ]
        ];
    }
}
