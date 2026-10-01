<?php

namespace App\Traits;

use App\Constants\Status;
use App\Models\Contact;
use App\Models\Conversation;
use libphonenumber\PhoneNumberUtil;

/**
 * Contact/conversation resolution for the coexistence webhook handlers.
 *
 * This intentionally duplicates the inline block inside WebhookController::webhookResponse() rather
 * than extracting it: that method is the hot path for every existing Cloud API account and must not
 * be restructured as part of this feature.
 */
trait ResolvesWhatsappContacts
{
    /**
     * Split a wa_id (digits only, no plus) into a country code and a national number.
     *
     * $strict additionally rejects numbers that parse but could never be dialled. libphonenumber
     * happily reads a five digit short code like "12345" as country 1 / national 2345, which would
     * turn every short code in a device address book into a contact and burn the plan's contact
     * limit. isPossibleNumber is the right gate rather than isValidNumber, because the latter also
     * rejects legitimately reachable numbers whose ranges its metadata does not know (US 555 test
     * numbers among them). Left off for message handling, where the number came from Meta and
     * dropping it would silently lose a message.
     *
     * @return array{0:int,1:string}|null null when the number cannot be parsed
     */
    protected function splitPhoneNumber(?string $number, bool $strict = false): ?array
    {
        $number = preg_replace('/\D/', '', (string) $number);

        if (!$number) {
            return null;
        }

        try {
            $util   = PhoneNumberUtil::getInstance();
            $parsed = $util->parse('+' . $number, '');
        } catch (\Throwable $ex) {
            // Device address books routinely contain short codes and local-format numbers.
            return null;
        }

        if ($strict && !$util->isPossibleNumber($parsed)) {
            return null;
        }

        return [$parsed->getCountryCode(), (string) $parsed->getNationalNumber()];
    }

    protected function findContact($user, int $countryCode, string $nationalNumber): ?Contact
    {
        return Contact::where('user_id', $user->id)
            ->where('mobile_code', $countryCode)
            ->where('mobile', $nationalNumber)
            ->first();
    }

    /**
     * Find or create the contact behind a wa_id. Returns null when the number is unparseable.
     */
    protected function resolveContact($user, ?string $waId, ?string $profileName = null): ?Contact
    {
        $split = $this->splitPhoneNumber($waId);

        if (!$split) {
            return null;
        }

        [$countryCode, $nationalNumber] = $split;

        $contact = $this->findContact($user, $countryCode, $nationalNumber);

        if ($contact) {
            return $contact;
        }

        $contact              = new Contact();
        $contact->firstname   = $profileName;
        $contact->mobile_code = $countryCode;
        $contact->mobile      = $nationalNumber;
        $contact->user_id     = $user->id;
        $contact->save();

        return $contact;
    }

    protected function resolveConversation($user, $whatsappAccount, Contact $contact): Conversation
    {
        $conversation = Conversation::where('contact_id', $contact->id)
            ->where('user_id', $user->id)
            ->where('whatsapp_account_id', $whatsappAccount->id)
            ->first();

        if ($conversation) {
            return $conversation;
        }

        $conversation                       = new Conversation();
        $conversation->contact_id           = $contact->id;
        $conversation->whatsapp_account_id  = $whatsappAccount->id;
        $conversation->user_id              = $whatsappAccount->user_id;
        $conversation->conversation_channel = Status::CHANNEL_WHATSAPP;
        $conversation->save();

        return $conversation;
    }

    /**
     * Only ever move a conversation's timestamp forward, so out-of-order deliveries cannot scramble
     * the inbox ordering.
     */
    protected function touchConversation(Conversation $conversation, $timestamp): void
    {
        if ($conversation->last_message_at && $conversation->last_message_at->gte($timestamp)) {
            return;
        }

        $conversation->last_message_at = $timestamp;
        $conversation->save();
    }
}
