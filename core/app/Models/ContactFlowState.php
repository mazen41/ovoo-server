<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactFlowState extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    public function flow()
    {
        return $this->belongsTo(Flow::class);
    }

    public function currentNode()
    {
        return $this->belongsTo(FlowNode::class, 'current_node_id', 'node_id');
    }
}
