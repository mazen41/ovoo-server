<?php

namespace App\Models;

use App\Traits\ApiQuery;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use ApiQuery;

    protected $casts = [
        'details' => 'array',
        'address' => 'object'
    ];

    protected $appends = [
        'image_src',
        'full_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function conversation()
    {
        return $this->hasOne(Conversation::class);
    }

    public function lists()
    {
        return $this->belongsToMany(ContactList::class, 'contact_list_contacts', 'contact_id', 'contact_list_id');
    }

    public function blockedBy()
    {
        return $this->belongsTo(User::class, 'blocked_by');
    }

    public function tags()
    {
        return $this->belongsToMany(ContactTag::class, 'contact_tag_contacts', "contact_id", 'contact_tag_id');
    }

    public function contactListContact()
    {
        return $this->belongsToMany(ContactList::class, 'contact_list_contacts', 'contact_id', 'contact_list_id');
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_contacts')->withTimestamps();
    }


    public function mobileNumber(): Attribute
    {
        return new Attribute(
            get: fn() => $this->mobile_code . " " . $this->mobile,
        );
    }

    public function imageSrc(): Attribute
    {
        return new Attribute(
            get: fn() =>  getImage(getFilePath('contactProfile') . '/' . $this->image, getFilePath('contactProfile'), isAvatar: true)
        );
    }

    public function fullName(): Attribute
    {
        return new Attribute(
            get: fn() => (
                auth()->check() && auth()->user()->hasAgentPermission('view contact name')
            )
                ? (
                    ($this->firstname || $this->lastname)
                    ? trim("{$this->firstname} {$this->lastname}")
                    : '+' . $this->mobileNumber
                )
                : (($this->firstname || $this->lastname)
                    ? trim("{$this->firstname} {$this->lastname}")
                    : '+' . $this->mobileNumber),
        );
    }

    public function fullNameShortForm(): Attribute
    {
        return new Attribute(
            get: fn() => strtoupper(substr($this->firstname, 0, 1)) . strtoupper(substr($this->lastname, 0, 1)),
        );
    }
}
