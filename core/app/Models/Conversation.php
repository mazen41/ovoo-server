<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\ApiQuery;
use Illuminate\Database\Eloquent\Model;
use Addons\TeleWpp\Source\Models\TelegramBot;

class Conversation extends Model
{
    use ApiQuery;

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage()
    {
        // Match conversationMessages() ordering. The id tiebreaker keeps same second
        // inserts resolving exactly as the previous latest('id') did.
        return $this->hasOne(Message::class)
            ->orderByRaw('COALESCE(ordering, created_at) DESC')
            ->orderBy('id', 'desc')
            ->take(1);
    }

    public function unseenMessages()
    {
        return $this->hasMany(Message::class)->where('type', Status::MESSAGE_RECEIVED)->whereIn('status', [Status::SENT, Status::DELIVERED]);
    }

    public function notes()
    {
        return $this->hasMany(ContactNote::class, 'conversation_id', 'id')->orderBy('id', 'desc');
    }

    public function flowStates()
    {
        return $this->hasMany(ContactFlowState::class);
    }
    public function telegramBot()
    {
        return $this->belongsTo(TelegramBot::class, 'telegram_bot_id');
    }
}
