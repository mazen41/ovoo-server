<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\PermissionRegistrar;
use ZipArchive;

class SystemController extends Controller
{
    /**
     * Top level directories an update package is allowed to write into. The document root is the
     * project root, so an update legitimately touches `core/`, `assets/` and `build/` - the
     * compiled flow-builder bundle - plus the two boot files that sit beside them.
     */
    private const ALLOWED_ROOTS = ['core/', 'assets/', 'build/'];

    private const ALLOWED_FILES = ['index.php', '.htaccess', 'update.json'];

    public function systemInfo()
    {
        $laravelVersion = app()->version();
        $timeZone       = config('app.timezone');
        $pageTitle      = 'Application Information';
        $serverDetails  = $_SERVER;
        $systemDetails  = systemDetails();
        return view('admin.system.info', compact('pageTitle', 'laravelVersion', 'timeZone', 'serverDetails', 'systemDetails'));
    }

    public function optimizeClear()
    {
        Artisan::call('optimize:clear');
        $notify[] = ['success', 'Cache cleared successfully'];
        return back()->withNotify($notify);
    }

    public function updateIndex()
    {
        $pageTitle     = 'System Update';
        $systemDetails = systemDetails();
        return view('admin.system.update', compact('pageTitle', 'systemDetails'));
    }

    /**
     * Apply an update package uploaded from the admin panel.
     *
     * The package overlays its files onto the existing tree and then runs only the migrations of
     * the release folders it declares. Both halves are idempotent, so re-uploading the same
     * package is the supported way to resume an update that failed part way through.
     */
    public function updateUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'update_zip' => 'required|file|mimes:zip|max:262144',
        ]);

        if ($validator->fails()) {
            return apiResponse('Validation failed', 'error', $validator->errors()->all(), [], 422);
        }

        try {
            if (!extension_loaded('zip')) {
                throw new \Exception('The PHP Zip extension is required to apply system updates. Please enable it in your php.ini or contact your hosting provider.');
            }

            $projectRoot = base_path('..');

            if (!is_writable($projectRoot) || !is_writable(base_path())) {
                throw new \Exception('The application directory is not writable. Please set the correct permissions or contact your hosting provider.');
            }

            $zipPath = $request->file('update_zip')->getRealPath();
            $meta    = $this->readUpdateJson($zipPath);

            $system  = systemDetails();
            $current = (string) $system['web_version'];

            if ((string) $meta['product'] !== (string) $system['name']) {
                throw new \Exception('This update package is for a different product (' . $meta['product'] . ').');
            }

            if (version_compare((string) $meta['version'], $current, '<')) {
                throw new \Exception('The uploaded package is version ' . $meta['version'] . ', which is older than the installed version ' . $current . '.');
            }

            if (!empty($meta['min_version']) && version_compare((string) $meta['min_version'], $current, '>')) {
                throw new \Exception('This package requires version ' . $meta['min_version'] . ' or later. Please apply the earlier updates first.');
            }

            $migrationDirs = $this->migrationDirectories($meta);

            $this->extractUpdate($zipPath, $projectRoot, $migrationDirs);

            $ledgerNote = $this->baselineLegacyLedger();

            foreach ($migrationDirs as $dir) {
                $path = database_path('migrations/' . $dir);

                if (!is_dir($path)) {
                    throw new \Exception('The migration folder ' . $dir . ' is missing after extraction.');
                }

                $exitCode = Artisan::call('migrate', [
                    '--path'     => $path,
                    '--realpath' => true,
                    '--force'    => true,
                ]);

                if ($exitCode !== 0) {
                    throw new \RuntimeException('The update migration failed: ' . trim(Artisan::output()));
                }
            }

            Cache::forget('installed_addons_active');
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            Artisan::call('optimize:clear');

            if (function_exists('opcache_reset')) {
                @opcache_reset();
            }

            $messages = ['The system was updated to v' . $meta['version'] . ' successfully.'];

            if ($ledgerNote) {
                $messages[] = $ledgerNote;
            }

            return apiResponse('System updated', 'success', $messages);
        } catch (\Throwable $e) {
            return apiResponse('System update failed', 'error', [$e->getMessage() ?: 'The system could not be updated.'], [], 422);
        }
    }

    /**
     * Heal a migrations ledger that predates the migration-based update mechanism.
     *
     * Installs whose schema was imported from a SQL dump rather than built by the wizard have every
     * table while their migrations table is empty or partial. The scoped `migrate --path` this
     * updater runs is unaffected by that, but a plain `php artisan migrate` later would try to build
     * the whole schema again. Recording the base migrations as applied — without running them —
     * closes that permanently.
     *
     * Marking a migration applied that has NOT actually run would strand the schema forever, so
     * this only writes when the install proves to be established and every table each pending
     * migration creates already exists. Anything less and it declines and leaves the ledger alone;
     * the update itself does not depend on this.
     */
    private function baselineLegacyLedger(): ?string
    {
        try {
            if (!Schema::hasTable('migrations')) {
                Artisan::call('migrate:install');
            }

            $applied = DB::table('migrations')->pluck('migration')->all();

            // Deliberately non-recursive: release folders under updates/ must stay pending so the
            // scoped migrate below can actually run them.
            $pending = collect(File::files(database_path('migrations')))
                ->reject(fn ($file) => $file->getExtension() !== 'php')
                ->reject(fn ($file) => in_array($file->getFilenameWithoutExtension(), $applied, true))
                ->values();

            if ($pending->isEmpty()) {
                return null;
            }

            // An established install has a settings row. Without one this could be a half-finished
            // install, where marking migrations applied would leave tables permanently missing.
            if (!Schema::hasTable('general_settings') || !DB::table('general_settings')->exists()) {
                return null;
            }

            foreach ($pending as $file) {
                foreach ($this->tablesCreatedBy($file->getPathname()) as $table) {
                    if (!Schema::hasTable($table)) {
                        return 'Some database tables are missing, so the migration history was left untouched. Please contact support before running any further updates.';
                    }
                }
            }

            $batch = (int) DB::table('migrations')->max('batch') + 1;
            $rows  = $pending->map(fn ($file) => [
                'migration' => $file->getFilenameWithoutExtension(),
                'batch'     => $batch,
            ])->sortBy('migration')->values()->all();

            DB::transaction(function () use ($rows) {
                DB::table('migrations')->insert($rows);
            });

            return 'Recorded ' . count($rows) . ' existing migration(s) in the update history. They were not re-run; your data is untouched.';
        } catch (\Throwable $e) {
            // Never fail an otherwise good update over bookkeeping.
            return 'The update completed, but the migration history could not be repaired: ' . $e->getMessage();
        }
    }

    /**
     * The tables a migration creates, read from its source. No `up()` in this project drops or
     * renames a table, so an existing table is proof the migration's work is already in place.
     */
    private function tablesCreatedBy(string $path): array
    {
        $source = File::get($path);

        // Only the up() half: down() is full of dropIfExists calls naming the same tables.
        $up = preg_split('/function\s+down\s*\(/', $source, 2)[0];

        preg_match_all('/Schema::create\(\s*[\'"]([A-Za-z0-9_]+)[\'"]/', $up, $matches);

        return $matches[1];
    }

    /**
     * Read update.json out of the package. Matched by basename so it does not matter whether the
     * archive was zipped with or without a wrapping folder.
     */
    private function readUpdateJson(string $zipPath): array
    {
        $zip = new ZipArchive();

        if ($zip->open($zipPath) !== true) {
            throw new \Exception('Could not open the update ZIP file.');
        }

        $json = null;

        for ($i = 0; $i < $zip->numFiles; $i++) {
            if (basename($zip->getNameIndex($i)) === 'update.json') {
                $json = json_decode($zip->getFromIndex($i), true);
                break;
            }
        }

        $zip->close();

        if (!$json || !is_array($json) || array_diff(['product', 'version'], array_keys($json))) {
            throw new \Exception('Invalid update package. The update.json file could not be found or is malformed.');
        }

        return $json;
    }

    /**
     * The release folders whose migrations this package wants run, validated so that a crafted
     * manifest cannot point the migrator at an arbitrary path.
     */
    private function migrationDirectories(array $meta): array
    {
        $declared = $meta['migrations'] ?? [];

        if (!is_array($declared)) {
            throw new \Exception('The migrations entry in update.json must be a list of folders.');
        }

        foreach ($declared as $dir) {
            if (!is_string($dir) || !preg_match('#^updates/[A-Za-z0-9_.\-]+$#', $dir)) {
                throw new \Exception('Invalid migration folder in update.json: ' . (is_string($dir) ? $dir : gettype($dir)));
            }
        }

        return array_values($declared);
    }

    /**
     * Overlay the package onto the project root. Existing files are overwritten, nothing is
     * deleted first, so files the package does not carry are left alone.
     */
    private function extractUpdate(string $zipPath, string $projectRoot, array $migrationDirs): void
    {
        $zip = new ZipArchive();

        if ($zip->open($zipPath) !== true) {
            throw new \Exception('Could not open the update ZIP file.');
        }

        try {
            $seenMigrationDirs = [];
            $entries           = [];

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entry = $zip->getNameIndex($i);

                // Reject anything that could escape the project root, and anything outside the
                // directories an update is allowed to touch.
                if (str_contains($entry, '..') || str_starts_with($entry, '/') || preg_match('#^[A-Za-z]:#', $entry)) {
                    throw new \Exception('The update package contains an unsafe file path: ' . $entry);
                }

                $allowed = in_array($entry, self::ALLOWED_FILES, true);

                foreach (self::ALLOWED_ROOTS as $root) {
                    if (str_starts_with($entry, $root)) {
                        $allowed = true;
                        break;
                    }
                }

                if (!$allowed) {
                    throw new \Exception('The update package contains a file outside the application directories: ' . $entry);
                }

                foreach ($migrationDirs as $dir) {
                    if (str_starts_with($entry, 'core/database/migrations/' . $dir . '/')) {
                        $seenMigrationDirs[$dir] = true;
                    }
                }

                // The manifest is metadata for this request only; the project root is the document
                // root, so writing it there would leave it publicly readable.
                if ($entry !== 'update.json') {
                    $entries[] = $entry;
                }
            }

            foreach ($migrationDirs as $dir) {
                if (empty($seenMigrationDirs[$dir])) {
                    throw new \Exception('update.json declares the migration folder ' . $dir . ', but the package does not contain it.');
                }
            }

            // extractTo() stops at the first file it cannot write and leaves the ones before it
            // already overwritten, so a permissions problem half way down the list would apply a
            // fraction of the package. The files are written one by one instead, which also lets a
            // file be replaced without being writable itself - see applyEntries().
            $pending = $this->entriesNeedingWrite($zip, $entries, $projectRoot);

            if ($pending) {
                $blocked = $this->unwritableDirectories($pending, $projectRoot);

                if ($blocked) {
                    throw new \Exception($this->describeUnwritable($blocked));
                }

                $this->applyEntries($zip, $pending, $projectRoot);
            }
        } finally {
            $zip->close();
        }
    }

    /**
     * The entries whose content is not already on disk.
     *
     * The first step of an update is often an unzip over FTP, and re-uploading the package is the
     * supported way to resume an interrupted run, so by the time this runs many files - sometimes all
     * of them - are already identical. Skipping those means the update asks nothing of folders it has
     * no reason to write in, which matters because an FTP unzip creates the folders it adds as the FTP
     * user, leaving them closed to the web server.
     */
    private function entriesNeedingWrite(ZipArchive $zip, array $entries, string $projectRoot): array
    {
        $root    = rtrim(str_replace('\\', '/', $projectRoot), '/');
        $pending = [];

        foreach ($entries as $entry) {
            // Directories arrive as their own entries; they get created with the files in them.
            if (str_ends_with($entry, '/')) {
                continue;
            }

            $target = $root . '/' . $entry;
            $stat   = $zip->statName($entry);

            if (!is_file($target) || $stat === false || $stat['size'] !== filesize($target)) {
                $pending[] = $entry;
                continue;
            }

            $contents = $zip->getFromName($entry);

            if ($contents === false || $contents !== @file_get_contents($target)) {
                $pending[] = $entry;
            }
        }

        return $pending;
    }

    /**
     * Write every entry into place, leaving the install untouched unless all of them can be staged.
     *
     * Each file is written to a temporary name beside its target and only then moved over it.
     * Replacing a file that way is governed by the permissions of the containing folder rather than
     * of the file itself, so an install whose files are owned by the FTP user - the usual shared
     * hosting arrangement - updates correctly even though PHP could not have written those files
     * directly. The move is also atomic, so a reader never sees a half-written file.
     */
    private function applyEntries(ZipArchive $zip, array $entries, string $projectRoot): void
    {
        $root   = rtrim(str_replace('\\', '/', $projectRoot), '/');
        $staged = [];

        try {
            foreach ($entries as $entry) {
                // Directories arrive as their own entries; they get created with the files in them.
                if (str_ends_with($entry, '/')) {
                    continue;
                }

                $target    = $root . '/' . $entry;
                $directory = dirname($target);

                if (!is_dir($directory) && !@mkdir($directory, 0755, true) && !is_dir($directory)) {
                    throw new \Exception('The update was not applied because the folder ' . dirname($entry)
                        . ' could not be created. Nothing has been changed.');
                }

                $contents = $zip->getFromName($entry);

                if ($contents === false) {
                    throw new \Exception('The update package is damaged: ' . $entry
                        . ' could not be read from it. Nothing has been changed.');
                }

                $temporary = $directory . '/.ovo-update-' . bin2hex(random_bytes(8));

                if (@file_put_contents($temporary, $contents) === false) {
                    throw new \Exception($this->describeUnwritable([trim(dirname($entry), '.')]));
                }

                // Keep whatever permissions the file already had; a new file gets the ordinary ones.
                @chmod($temporary, file_exists($target) ? (fileperms($target) & 0777) : 0644);

                $staged[$temporary] = $target;
            }
        } catch (\Throwable $e) {
            foreach (array_keys($staged) as $temporary) {
                @unlink($temporary);
            }

            throw $e;
        }

        $pending = $staged;

        foreach ($staged as $temporary => $target) {
            unset($pending[$temporary]);

            if (@rename($temporary, $target)) {
                continue;
            }

            // The files already moved stay in place - they are the new versions, and re-uploading the
            // package applies the rest - but the ones still waiting are cleaned up so no stray
            // temporary files are left in the installation.
            @unlink($temporary);

            foreach (array_keys($pending) as $leftover) {
                @unlink($leftover);
            }

            throw new \Exception('The update could not replace ' . trim(substr($target, strlen($root)), '/')
                . '. Correct the file permissions and upload the package again - re-uploading it'
                . ' re-applies every file, so the update will finish.');
        }
    }

    /**
     * The folders the web server cannot create files in, as paths relative to the project root.
     *
     * Because applyEntries() replaces a file by moving a new one over it, what has to be writable is
     * the folder holding the file, not the file. Folders the package itself adds do not exist yet,
     * so the nearest ancestor that does exist is what gets tested.
     */
    private function unwritableDirectories(array $entries, string $projectRoot): array
    {
        $blocked = [];
        $root    = rtrim(str_replace('\\', '/', $projectRoot), '/');

        foreach ($entries as $entry) {
            $directory = dirname($root . '/' . rtrim($entry, '/'));

            while (!file_exists($directory) && strlen($directory) > strlen($root)) {
                $directory = dirname($directory);
            }

            if (!is_writable($directory)) {
                $blocked[trim(substr($directory, strlen($root)), '/')] = true;
            }
        }

        return array_keys($blocked);
    }

    /**
     * Say which folders blocked the update and how to fix them, without making the admin read a log.
     */
    private function describeUnwritable(array $blocked): string
    {
        $shown = array_map(
            fn ($path) => $path === '' ? 'the main OvoWPP folder' : $path,
            array_slice($blocked, 0, 5)
        );
        $more = count($blocked) - count($shown);

        return 'The update was not applied because the web server cannot create files in: '
            . implode(', ', $shown)
            . ($more > 0 ? ' (and ' . $more . ' more)' : '')
            . '. Nothing has been changed. Give the web server user ownership of the OvoWPP folder'
            . ' (for example: chown -R www:www /path/to/ovowpp) and upload the package again.';
    }
}
