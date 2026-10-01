<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentPermission extends Model
{
    public function agents()
    {
        return $this->belongsToMany(User::class,'agent_has_permissions','agent_permission_id','agent_id');
    }
}
