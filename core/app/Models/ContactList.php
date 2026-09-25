<?php

namespace App\Models;

use App\Traits\ApiQuery;
use Illuminate\Database\Eloquent\Model;

class ContactList extends Model
{
    use ApiQuery;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contact()
    {
        return $this->belongsToMany(Contact::class, 'contact_list_contacts', 'contact_list_id', 'contact_id');
    }
}
