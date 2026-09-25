<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlowNode extends Model
{

    protected $casts = [
        'buttons_json' => 'array',
        'location' => 'array'
    ];

    public function flow()
    {
        return $this->belongsTo(Flow::class, 'flow_id', 'id');
    }

    public function media()
    {
        return $this->hasOne(FlowNodeMedia::class, 'flow_node_id', 'node_id');
    }

    public function targetNode()
    {
        return $this->belongsTo(FlowNode::class, 'target_node_id', 'node_id');
    }

    public function states()
    {
        return $this->hasMany(ContactFlowState::class, 'current_node_id', 'node_id');
    }
}
