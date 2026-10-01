<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\ApiQuery;
use App\Traits\UserNotify;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use UserNotify, HasApiTokens, ApiQuery;
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'ver_code',
        'balance',
        'kyc_data'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'kyc_data'          => 'object',
        'ver_code_send_at'  => 'datetime'
    ];


    /**
     * specified column for export with column manipulation
     *
     * @var array
     */
    public function exportColumns(): array
    {
        return  [
            'firstname',
            'lastname',
            'username',
            'email',
            'mobile',
            "country_name",
            "created_at" => [
                'name' => "Joined At",
                'callback' => function ($item) {
                    return showDateTime($item->created_at, lang: 'en');
                }
            ],
            "balance" => [
                'callback' => function ($item) {
                    return showAmount($item->balance);
                }
            ]
        ];
    }

    public function plan()
    {
        return $this->belongsTo(PricingPlan::class, 'plan_id');
    }

    public function aiSetting()
    {
        return $this->hasOne(AiUserSetting::class,'user_id');
    }

    public function currentWhatsapp()
    {
        return $this->whatsappAccounts()->where('is_default', Status::YES)->first();
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function agentPermissions()
    {
        return $this->belongsToMany(AgentPermission::class, 'agent_has_permissions', 'agent_id', 'agent_permission_id');
    }

    public function hasAgentPermission($permission)
    {
        if ($this->is_agent) {
            return $this->agentPermissions()->where('name', $permission)->exists();
        }
        return true;
    }

    public function hasAnyAgentPermission($permissions = [])
    {
        if ($this->is_agent) {
            return $this->agentPermissions()->whereIn('name', $permissions)->exists();
        }
        return true;
    }

    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    public function purchases()
    {
        return $this->hasMany(PlanPurchase::class);
    }

    public function whatsappAccounts()
    {
        return $this->hasMany(WhatsappAccount::class);
    }

    public function loginLogs()
    {
        return $this->hasMany(UserLogin::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class)->orderBy('id', 'desc');
    }

    public function userApiCredential() {
        return $this->hasOne(UserApiCredentials::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class)->where('status', '!=', Status::PAYMENT_INITIATE);
    }

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class)->where('status', '!=', Status::PAYMENT_INITIATE);
    }

    public function tickets()
    {
        return $this->hasMany(SupportTicket::class);
    }

    public function externalApiIpWhiteLists() {
        return $this->hasMany(ExternalApiIpWhiteList::class);
    }

    public function fullname(): Attribute
    {
        return new Attribute(
            get: fn() => $this->firstname . ' ' . $this->lastname,
        );
    }

    public function notes()
    {
        return $this->hasMany(ContactNote::class);
    }

    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function imageSrc(): Attribute
    {
        return new Attribute(
            get: fn() => getImage(getFilePath('userProfile') . '/' . $this->image, getFilePath('userProfile'), isAvatar: true),
        );
    }

    public function fullNameShortForm(): Attribute
    {
        return new Attribute(
            get: fn() => strtoupper(substr($this->firstname, 0, 1)) . strtoupper(substr($this->lastname, 0, 1)),
        );
    }

    public function mobileNumber(): Attribute
    {
        return new Attribute(
            get: fn() => $this->dial_code . $this->mobile,
        );
    }

    // SCOPES
    public function scopeAgent($query)
    {
        return $query->whereHas('parent')->where('is_agent', Status::YES);
    }

    public function scopeActive($query)
    {
        return $query->where('status', Status::USER_ACTIVE)->where('ev', Status::VERIFIED)->where('sv', Status::VERIFIED);
    }

    public function scopeBanned($query)
    {
        return $query->where('status', Status::USER_BAN);
    }

    public function scopeEmailUnverified($query)
    {
        return $query->where('ev', Status::UNVERIFIED);
    }

    public function scopeMobileUnverified($query)
    {
        return $query->where('sv', Status::UNVERIFIED);
    }

    public function scopeKycUnverified($query)
    {
        return $query->where('kv', Status::KYC_UNVERIFIED);
    }

    public function scopeKycPending($query)
    {
        return $query->where('kv', Status::KYC_PENDING);
    }

    public function scopeEmailVerified($query)
    {
        return $query->where('ev', Status::VERIFIED);
    }

    public function scopeMobileVerified($query)
    {
        return $query->where('sv', Status::VERIFIED);
    }

    public function scopeWithBalance($query)
    {
        return $query->where('balance', '>', 0);
    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);
    }
}
