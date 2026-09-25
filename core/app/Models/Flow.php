<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Flow extends Model
{
    use GlobalStatus;
    
    public function nodes()
    {
        return $this->hasMany(FlowNode::class);
    }

    public function scopeNewMessage($query)
    {
        $query->where('trigger_type', Status::FLOW_TRIGGER_NEW_MESSAGE);
    }

    public function getTriggerType(): Attribute
    {
        return new Attribute(function () {
            return $this->trigger_type == Status::FLOW_TRIGGER_NEW_MESSAGE ? 'New Message' : 'Keyword Match'.' ('.$this->keyword.')';
        });
    }
    
    public function scopeKeywordMatch($query)
    {
        $query->where('trigger_type', Status::FLOW_TRIGGER_KEYWORD_MATCH);
    }
}
