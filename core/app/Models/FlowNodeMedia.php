<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlowNodeMedia extends Model
{
    public function node()
    {
        return $this->belongsTo(FlowNode::class, 'flow_node_id', 'id');
    }
}
