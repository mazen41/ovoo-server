<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\ApiQuery;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use ApiQuery;

    protected $casts = [
        'location'     => 'array',
        'list_reply'   => 'array',
        'product_data' => 'array'
    ];

    protected $fillable = ['campaign_id'];

    public function node()
    {
        return $this->belongsTo(FlowNode::class, 'flow_node_id', 'id');
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function contact():Attribute
    {
        return new Attribute(function () {
            return $this->conversation?->contact;
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replyTo()
    {
        return $this->belongsTo(self::class,'reply_to_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id')
            ->where('is_agent', Status::YES);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function ctaUrl()
    {
        return $this->belongsTo(CtaUrl::class, 'cta_url_id');
    }

    public function interactiveList()
    {
        return $this->belongsTo(InteractiveList::class, 'interactive_list_id');
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';

            if ($this->status == Status::SENT) {
                $html = '<i class="la la-check text--secondary"></i>';
            } elseif ($this->status == Status::DELIVERED) {
                $html = '<i class="la la-check-double text--secondary"></i>';
            } elseif ($this->status == Status::READ) {
                $html = '<i class="la la-check-double text--success"></i>';
            } else {
                // A device or history originated message was never sent by the platform, so
                // resending it would post content the customer already has. message_origin
                // defaults to platform, so existing rows keep the resend control.
                if ($this->template || $this->message_origin != Status::MESSAGE_ORIGIN_PLATFORM) {
                    $html = '<span class="text--danger text--bold " data-bs-toggle-custom-two="tooltip" data-bs-placement="left" data-bs-title="' . trans($this->error_message ?? 'Something went wrong') . '" data-bs-custom-class="custom-tooltip" >' . trans('Failed') . '</span>';
                } else {
                    $html = '<span class="text--danger text--bold" data-bs-toggle-custom-two="tooltip" data-bs-placement="left" data-bs-title="' . trans($this->error_message ?? 'Something went wrong') . '" data-bs-custom-class="custom-tooltip" ><i title="' . trans('Resend') . '" class="las la-redo-alt text--warning resender" data-id="' . e($this->id) . '"></i></span>';
                }
            }
            return $html;
        });
    }
}
