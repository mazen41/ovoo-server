<?php

namespace App\Console\Commands;

use App\Lib\WhatsApp\WhatsAppLib;
use App\Models\Message;
use App\Models\WhatsappAccount;
use Exception;
use Illuminate\Console\Command;

/**
 * Backfills media_url / media_path for messages that have a media_id but were
 * stored before media download worked for every type.
 *
 * Default is a DRY RUN: it only reads the database and asks Meta whether each
 * media id can still be resolved. Pass --apply to download files and save rows.
 */
class BackfillWhatsappMedia extends Command
{
    protected $signature = 'whatsapp:backfill-media
        {--apply : Download the files and update the messages. Without this flag nothing is changed.}
        {--limit=0 : Stop after this many messages (0 = no limit)}';

    protected $description = 'Download WhatsApp media for messages with media_id but no media_path (dry run unless --apply)';

    public function handle(): int
    {
        $apply = (bool) $this->option('apply');
        $limit = (int) $this->option('limit');

        $query = Message::query()
            ->whereNotNull('media_id')
            ->where('media_id', '!=', '')
            ->where(function ($q) {
                $q->whereNull('media_path')->orWhere('media_path', '');
            });

        $total = (clone $query)->count();

        $this->info($apply ? 'MODE: APPLY (files will be downloaded and rows updated)' : 'MODE: DRY RUN (no changes will be made)');
        $this->line("Messages with media_id and no media_path: {$total}");

        if ($total === 0) {
            return self::SUCCESS;
        }

        $lib          = new WhatsAppLib();
        $processed    = 0;
        $downloadable = 0;
        $unavailable  = 0;
        $saved        = 0;
        $stopped      = false;

        $query->orderBy('id')->chunkById(100, function ($messages) use (
            $lib, $apply, $limit, &$processed, &$downloadable, &$unavailable, &$saved, &$stopped
        ) {
            foreach ($messages as $message) {
                if ($limit > 0 && $processed >= $limit) {
                    $stopped = true;
                    return false;
                }
                $processed++;

                $account = WhatsappAccount::find($message->whatsapp_account_id);
                if (!$account || !$account->access_token) {
                    $unavailable++;
                    $this->warn("Message {$message->id}: WhatsApp account or access token not found");
                    continue;
                }

                try {
                    // Asks Meta for the media URL. Fails if the media has expired or was deleted.
                    $mediaData = $lib->getMediaUrl($message->media_id, $account->access_token);
                    $downloadable++;

                    if ($apply) {
                        $path = $lib->storedMediaToLocal(
                            $mediaData['url'],
                            $message->media_id,
                            $account->access_token,
                            $message->user_id,
                            $message->mime_type
                        );
                        $message->media_url  = $mediaData['url'];
                        $message->media_path = $path;
                        $message->save();
                        $saved++;
                    }
                } catch (Exception $ex) {
                    $unavailable++;
                    $this->warn("Message {$message->id}: " . $ex->getMessage());
                }
            }
        });

        $this->newLine();
        $this->info("Checked: {$processed}");
        $this->info("Still downloadable from Meta: {$downloadable}");
        $this->info("Not retrievable (expired, deleted or account error): {$unavailable}");

        if ($apply) {
            $this->info("Saved to disk and updated: {$saved}");
        } else {
            $this->comment('Dry run complete. Re-run with --apply to download and update the rows.');
        }

        if ($stopped) {
            $this->comment("Stopped at --limit={$limit}. Run again to process the rest.");
        }

        return self::SUCCESS;
    }
}
