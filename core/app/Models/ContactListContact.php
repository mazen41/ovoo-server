<?php

namespace App\Models;

use App\Traits\ApiQuery;
use Illuminate\Database\Eloquent\Model;

class ContactListContact extends Model
{
    use ApiQuery;
    
    public function contact()
    {
        return $this->belongsTo(Contact::class, 'contact_id');
    }
}
