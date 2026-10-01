<?php

namespace App\Models;

use App\Constants\Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class CampaignContact extends Model
{
    protected $guard = ['id'];

    protected $casts = [
        'error_message' => 'object'
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * specified column for export with column manipulation 
     *
     * @var array
     */
    public function exportColumns(): array
    {
        return  [
            'campaign_id' => [
                'name' => "Campaign",
                "callback" => function ($item) {
                    return $item->campaign->title;
                }
            ],
            'contact_id' => [
                'name' => "Contact",
                "callback" => function ($item) {
                    return $item->contact->mobileNumber;
                }
            ],
            'status' => [
                'name' => "Status",
                "callback" => function ($item) {
                    return strip_tags($item->statusBadge);
                }
            ],
            'send_at' => [
                'name'     => "Send At",
                "callback" => function ($item) {
                    return showDateTime($item->send_at, lang: 'en');
                }
            ],
            'created_at' => [
                'name'     => "Created At",
                "callback" => function ($item) {
                    return showDateTime($item->created_at, lang: 'en');
                }
            ],
            'updated_at' => [
                'name'     => "Updated At",
                "callback" => function ($item) {
                    return showDateTime($item->updated_at, lang: 'en');
                }
            ]
        ];
    }

}
