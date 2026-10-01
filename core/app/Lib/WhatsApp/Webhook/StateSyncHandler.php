<?php

namespace App\Lib\WhatsApp\Webhook;

use App\Models\Contact;
use App\Traits\ResolvesWhatsappContacts;
use Illuminate\Support\Facades\Log;

/**
 * Handles the `smb_app_state_sync` webhook field: the address book of the phone running the WhatsApp
 * Business app.
 *
 * This only ever creates contacts. It writes no messages, opens no conversations and broadcasts
 * nothing - a contact-book sync is not a conversation event, and a device address book can hold
 * thousands of entries.
 */
class StateSyncHandler
{
    use ResolvesWhatsappContacts;

    public function handle($whatsappAccount, $user, array $value): void
    {
        foreach ($value['state_sync'] ?? [] as $state) {
            if (!is_array($state) || ($state['type'] ?? null) !== 'contact') {
                continue;
            }

            $action = $state['action'] ?? null;

            if ($action === 'add') {
                $this->addContact($user, $state['contact'] ?? []);
                continue;
            }

            if ($action === 'remove') {
                // Deliberately non-destructive. Removing a contact from a phone is not an instruction
                // to delete it from the CRM, and a delete would cascade into conversations and
                // message history in the UI.
                Log::info('Coexistence contact removal ignored', [
                    'whatsapp_account_id' => $whatsappAccount->id,
                    'phone_number'        => $state['contact']['phone_number'] ?? null,
                ]);
            }
        }
    }

    protected function addContact($user, array $contactData): void
    {
        $split = $this->splitPhoneNumber($contactData['phone_number'] ?? null, strict: true);

        if (!$split) {
            return;
        }

        [$countryCode, $nationalNumber] = $split;

        $firstname = $contactData['first_name'] ?? null;
        $lastname  = $this->lastname($contactData['full_name'] ?? null, $firstname);

        $contact = $this->findContact($user, $countryCode, $nationalNumber);

        if ($contact) {
            // Only fill blanks - never overwrite a name the user curated in the CRM.
            $changed = false;

            if (!$contact->firstname && $firstname) {
                $contact->firstname = $firstname;
                $changed            = true;
            }

            if (!$contact->lastname && $lastname) {
                $contact->lastname = $lastname;
                $changed           = true;
            }

            if ($changed) {
                $contact->save();
            }

            return;
        }

        // A device address book can hold thousands of entries, so the plan limit has to apply here
        // exactly as it does to a manual import.
        if (!featureAccessLimitCheck($user->contact_limit)) {
            return;
        }

        $contact              = new Contact();
        $contact->user_id     = $user->id;
        $contact->firstname   = $firstname;
        $contact->lastname    = $lastname;
        $contact->mobile_code = $countryCode;
        $contact->mobile      = $nationalNumber;
        $contact->save();

        decrementFeature($user, 'contact_limit');
    }

    protected function lastname(?string $fullName, ?string $firstname): ?string
    {
        $fullName = trim((string) $fullName);

        if (!$fullName) {
            return null;
        }

        if ($firstname && str_starts_with($fullName, $firstname)) {
            return trim(substr($fullName, strlen($firstname))) ?: null;
        }

        $parts = preg_split('/\s+/', $fullName);

        return count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : null;
    }
}
