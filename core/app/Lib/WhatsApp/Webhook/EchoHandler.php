<?php

namespace App\Lib\WhatsApp\Webhook;

use App\Constants\Status;
use App\Events\ReceiveMessage;
use App\Models\Message;
use App\Traits\ResolvesWhatsappContacts;
use Carbon\Carbon;

/**
 * Handles the `smb_message_echoes` webhook field.
 *
 * An echo is a message the business owner sent from the WhatsApp Business app on their phone. It has
 * already been delivered to the customer, so it is recorded as an outbound message and every
 * outbound side effect is suppressed: no welcome message, no automation flow, no AI auto-reply, no
 * notification and no plan decrement. Only the Pusher broadcast is kept, because an agent watching
 * the shared inbox has to see what the owner just typed on their handset.
 */
class EchoHandler
{
    use ResolvesWhatsappContacts;

    public function handle($whatsappAccount, $user, array $value): void
    {
        foreach ($value['message_echoes'] ?? [] as $echo) {
            if (is_array($echo)) {
                $this->storeEcho($whatsappAccount, $user, $echo);
            }
        }
    }

    protected function storeEcho($whatsappAccount, $user, array $echo): void
    {
        $whatsappMessageId = $echo['id'] ?? null;

        // `from` is our own business number; the customer is on `to`.
        $customerNumber = $echo['to'] ?? null;

        if (!$whatsappMessageId || !$customerNumber) {
            return;
        }

        // Meta redelivers echoes, and a retry can land while the first delivery is still in flight,
        // so a bare exists() check is not enough on its own.
        if (!cache()->add('coexistence-wamid:' . $whatsappMessageId, true, 60)) {
            return;
        }

        if (Message::where('whatsapp_message_id', $whatsappMessageId)->exists()) {
            return;
        }

        $contact = $this->resolveContact($user, $customerNumber);

        if (!$contact) {
            return;
        }

        $conversation = $this->resolveConversation($user, $whatsappAccount, $contact);

        $newContact = $contact->wasRecentlyCreated || $conversation->wasRecentlyCreated;

        $type      = $echo['type'] ?? 'text';
        $payload   = is_array($echo[$type] ?? null) ? $echo[$type] : [];
        $timestamp = isset($echo['timestamp'])
            ? Carbon::createFromTimestamp((int) $echo['timestamp'], config('app.timezone'))
            : Carbon::now();

        $message                      = new Message();
        $message->whatsapp_account_id = $whatsappAccount->id;
        $message->whatsapp_message_id = $whatsappMessageId;
        $message->user_id             = $user->id ?? 0;
        $message->conversation_id     = $conversation->id;
        $message->message             = $this->messageBody($type, $payload);
        $message->type                = Status::MESSAGE_SENT;
        $message->message_origin      = Status::MESSAGE_ORIGIN_DEVICE;
        $message->status              = Status::SENT;
        // getIntMessageType() returns null for sticker/reaction/contacts/order/unsupported, and the
        // column is NOT NULL, so fall back to text.
        $message->message_type        = getIntMessageType($type) ?? Status::TEXT_TYPE_MESSAGE;
        $message->reply_to_id         = 0;
        $message->channel             = Status::CHANNEL_WHATSAPP;
        $message->ordering            = $timestamp;

        if ($type === 'location') {
            $message->location = [
                'latitude'  => $payload['latitude'] ?? null,
                'longitude' => $payload['longitude'] ?? null,
                'name'      => $payload['name'] ?? null,
                'address'   => $payload['address'] ?? null,
            ];
        }

        if (!empty($payload['id'])) {
            $message->media_id       = $payload['id'];
            $message->media_type     = $type;
            $message->mime_type      = $payload['mime_type'] ?? null;
            $message->media_caption  = $payload['caption'] ?? null;
            $message->media_filename = $payload['filename'] ?? null;
            // The binary itself is fetched later by CronController::coexistenceMediaSync(); doing it
            // inline would risk Meta's webhook timeout.
            $message->media_sync_status = Status::MEDIA_SYNC_PENDING;
        }

        $message->save();

        $this->touchConversation($conversation, $timestamp);

        $this->broadcast($whatsappAccount, $conversation, $message, $timestamp, $newContact);
    }

    protected function messageBody(string $type, array $payload): string
    {
        return match ($type) {
            'text'     => $payload['body'] ?? '',
            'button'   => $payload['text'] ?? '',
            'reaction' => $payload['emoji'] ?? '',
            default    => $payload['caption'] ?? '',
        };
    }

    protected function broadcast($whatsappAccount, $conversation, Message $message, Carbon $timestamp, bool $newContact = false): void
    {
        $html                        = view('Template::user.inbox.single_message', compact('message'))->render();
        $lastConversationMessageHtml = view('Template::user.inbox.conversation_last_message', compact('message'))->render();
        $unseenMessages              = $conversation->unseenMessages()->count();

        event(new ReceiveMessage($whatsappAccount->id, [
            'html'            => $html,
            'message'         => $message,
            'newMessage'      => true,
            'newContact'      => $newContact,
            'lastMessageHtml' => $lastConversationMessageHtml,
            'unseenMessage'   => $unseenMessages < 10 ? $unseenMessages : '9+',
            'lastMessageAt'   => showDateTime($timestamp),
            'conversationId'  => $conversation->id,
            'mediaPath'       => s3_configured() ? url(getFilePath('conversation')) . '/' : getFilePath('conversation') . '/',
        ]));
    }
}
