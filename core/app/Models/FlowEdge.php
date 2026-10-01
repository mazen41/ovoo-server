<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlowEdge extends Model
{
    public $timestamps = false;

    public function flow()
    {
        return $this->belongsTo(Flow::class,'flow_id', 'id');
    }

    public function sourceNode()
    {
        return $this->belongsTo(FlowNode::class, 'source_node_id', 'node_id');
    }

    public function targetNode()
    {
        return $this->belongsTo(FlowNode::class, 'target_node_id', 'node_id');
    }

}
