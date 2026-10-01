# Coexistence webhook fixtures

Hand-craftable payloads for exercising the coexistence webhook path without a real Meta
account. `/webhook` is CSRF exempt (see `VerifyCsrfToken::$except`), so `curl` works directly.

## Setup

Pick a development `whatsapp_accounts` row and flip it to coexistence:

```sql
UPDATE whatsapp_accounts SET is_coexistence = 1 WHERE id = <id>;
```

Then replace the placeholders in every file:

| Placeholder | Replace with |
| --- | --- |
| `REPLACE_WABA_ID` | that row's `whatsapp_business_account_id` |
| `REPLACE_PHONE_NUMBER_ID` | that row's `phone_number_id` |
| `REPLACE_CLOUD_API_WABA_ID` | a **different** row that is still `is_coexistence = 0` |
| `REPLACE_CLOUD_API_PHONE_NUMBER_ID` | that same Cloud API row's `phone_number_id` |

`15550001111` is the business number and `15557654321` the customer; change them if your
dev data needs it. Note the echo's `from` is the **business** number, so the contact is
resolved from `to`.

## Running one

```bash
curl -sS -X POST http://localhost/webhook \
  -H 'Content-Type: application/json' \
  --data @tests/Fixtures/coexistence/echo_text.json
```

Every fixture must return HTTP 200 with `{"status":"received"}`.

## What each one asserts

| Fixture | Assertions |
| --- | --- |
| `echo_text.json` | One `messages` row with `type=1`, `status=1`, `message_origin=2`. **No** `notifications` row, no welcome message, no `ContactFlowState`. POST it twice: still exactly one row |
| `echo_image.json` | `media_sync_status=1`, `media_id` set, `media_path` null |
| `echo_sticker.json` | Insert succeeds with `message_type=1` on strict mode MySQL (`getIntMessageType` returns null for sticker) |
| `state_sync_add.json` | One `contacts` row; `users.contact_limit` decremented by exactly 1. Re-post: no second contact, no second decrement |
| `state_sync_remove.json` | The contact **still exists**. Nothing is deleted |
| `state_sync_garbage.json` | HTTP 200, no exception, no contact rows created |
| `negative_control_cloud_account.json` | Nothing written at all (the `!is_coexistence` bail) |
| `legacy_control_inbound_message.json` | Byte identical row set to a pre-change run |

## Media cron

```bash
curl -sS 'http://localhost/cron'
```

Rows whose `ordering` is older than 14 days must flip to `media_sync_status=3` with **no**
HTTP call to Meta. Recent rows get `media_path` set and `media_url` stored as a **string**
(the regression guard for the array-into-text-column bug).
