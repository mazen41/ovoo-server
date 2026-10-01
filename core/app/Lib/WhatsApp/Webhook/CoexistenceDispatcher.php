<?php

namespace App\Lib\WhatsApp\Webhook;

use App\Models\User;
use App\Models\WhatsappAccount;

/**
 * Entry point for the webhook fields that only ever arrive for a WhatsApp Coexistence number.
 *
 * WebhookController::webhookResponse() calls handles() before it touches anything else; when this
 * returns true the legacy body is skipped entirely. Meta never mixes these fields with
 * `messages`/`statuses` inside a single delivery, so switching on the whole payload is safe and
 * keeps the Cloud API path byte-identical.
 */
class CoexistenceDispatcher
{
    public const FIELDS = ['smb_message_echoes', 'smb_app_state_sync'];

    public function handles(array $entry): bool
    {
        foreach ($entry as $entryItem) {
            if (!is_array($entryItem)) {
                continue;
            }

            foreach ($entryItem['changes'] ?? [] as $change) {
                if (is_array($change) && in_array($change['field'] ?? '', self::FIELDS, true)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function dispatch(array $entry)
    {
        foreach ($entry as $entryItem) {
            if (!is_array($entryItem)) {
                continue;
            }

            $wabaId = $entryItem['id'] ?? null;

            foreach ($entryItem['changes'] ?? [] as $change) {
                if (!is_array($change)) {
                    continue;
                }

                $field = $change['field'] ?? null;
                $value = $change['value'] ?? null;

                if (!is_array($value) || !in_array($field, self::FIELDS, true)) {
                    continue;
                }

                $account = $this->resolveAccount($value, $wabaId);

                // Second safety net: even a misconfigured app subscribing to the SMB fields on a
                // plain Cloud API number must not be able to write anything.
                if (!$account || !$account->is_coexistence) {
                    continue;
                }

                $user = User::active()->find($account->user_id);

                if (!$user) {
                    continue;
                }

                try {
                    match ($field) {
                        'smb_message_echoes' => (new EchoHandler())->handle($account, $user, $value),
                        'smb_app_state_sync' => (new StateSyncHandler())->handle($account, $user, $value),
                    };
                } catch (\Throwable $ex) {
                    // Never bubble up: a non-200 makes Meta redeliver the whole payload.
                    report($ex);
                }
            }
        }

        return response()->json(['status' => 'received'], 200);
    }

    /**
     * Resolve by phone_number_id first. A WABA can hold several numbers, so matching on the WABA id
     * alone (as the legacy path does) picks an arbitrary one.
     */
    protected function resolveAccount(array $value, ?string $wabaId): ?WhatsappAccount
    {
        $phoneNumberId = $value['metadata']['phone_number_id'] ?? null;

        if ($phoneNumberId) {
            $account = WhatsappAccount::where('phone_number_id', $phoneNumberId)->first();

            if ($account) {
                return $account;
            }
        }

        return $wabaId
            ? WhatsappAccount::where('whatsapp_business_account_id', $wabaId)->first()
            : null;
    }
}
