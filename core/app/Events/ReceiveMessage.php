<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReceiveMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $whatsAppAccountId;
    public $data;

    public function __construct($whatsAppAccountId, $data = [])
    {
        $this->whatsAppAccountId = $whatsAppAccountId;
        $this->data              = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('receive-message-' . $this->whatsAppAccountId),
        ];
    }

    public function broadcastAs()
    {
        return 'receive-message';
    }
}
