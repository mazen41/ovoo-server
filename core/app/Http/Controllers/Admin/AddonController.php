<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\InstalledAddon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\PermissionRegistrar;
use ZipArchive;

class AddonController extends Controller
{
    public function index()
    {
        $pageTitle = 'Addon Manager';
        $addons    = InstalledAddon::latest()->get();
        return view('admin.addons.index', compact('pageTitle', 'addons'));
    }

    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'addon_zip'       => 'required|file|mimes:zip',
            'purchase_code'   => 'required|string',
            'envato_username' => 'required|string',
        ]);

        if ($validator->fails()) {
            return apiResponse('Validation failed', "error", $validator->errors()->all());
        }

        $existsAddon = InstalledAddon::where('purchase_code', $request->purchase_code)->exists();

        if ($existsAddon) {
            return apiResponse('addon_already_installed', "error", ['This addon is already uploaded to your server.']);
        }

        //check server requirements
        try {
            $this->checkPHPAndServerRequirements();
        } catch (\Exception $e) {
            $message = $e->getMessage() ?? 'Server does not meet the requirements to install addons. Please contact your hosting provider.';
            return apiResponse('Validation failed', "error", [$message]);
        }

        $purchaseCode = trim($request->purchase_code);
        $envatoUsername = trim($request->envato_username);

        // Store ZIP temporarily
        $zipFile = $request->file('addon_zip');
        $zipPath = storage_path('app/temp/addon/' . $zipFile->getClientOriginalName());
        $zipFile->move(storage_path('app/temp/addon'), $zipFile->getClientOriginalName());

        try {
            $meta = $this->readAddonJson($zipPath);

            $verification = $this->verifyAddonPurchase($purchaseCode, $envatoUsername, $meta['slug']);

            if ($verification['status'] == 'error') {
                return apiResponse('error', 'error', [$verification['message']]);
            }

            ///Read addon.json from ZIP without extracting
            $destinationAfterExtractZip = $this->extractZip($zipPath, $meta['name']);

            @unlink($zipPath);

            // Schema first. Code all over the application gates itself on addonIsInstalled(), so
            // the installed_addons row must not appear until the columns and tables that code
            // expects actually exist.
            $this->runAddonMigrations($meta['name']);

            $newAddon                  = new InstalledAddon();
            $newAddon->name            = $meta['name'];
            $newAddon->title           = $meta['title'];
            $newAddon->slug            = $meta['slug'];
            $newAddon->description     = $meta['description'];
            $newAddon->provider        = $meta['provider'];
            $newAddon->author          = $meta['author'];
            $newAddon->version         = $meta['version'] ?? '1.0';
            $newAddon->purchase_code   = $purchaseCode;
            $newAddon->envato_username = $envatoUsername;
            $newAddon->save();

            // Clear caches
            Cache::forget('installed_addons_active');

            app(PermissionRegistrar::class)->forgetCachedPermissions();

            return apiResponse('success', "success", ["Addon installed successfully!"]);
        } catch (\Exception $e) {
            @unlink($zipPath);

            // Undo any schema the addon managed to apply before it failed, so a retry starts clean.
            if (isset($meta['name'])) {
                $this->rollbackAddonMigrations($meta['name']);
            }

            // The row is written last, but a failure after that point would otherwise leave the
            // addon advertised as installed on top of schema that has just been rolled back.
            if (isset($newAddon) && $newAddon->exists) {
                $newAddon->delete();
                Cache::forget('installed_addons_active');
            }

            $message = $e->getMessage() ?? 'An error occurred during installation. Please try again.';
            return apiResponse('Installation failed', "error", ['Installation failed: ' . $message]);
        }
    }

    /**
     * Apply an addon's schema changes.
     *
     * Addons ship versioned Laravel migrations rather than a raw SQL dump. Executing a .sql file
     * that arrived inside an uploaded archive would hand arbitrary statements straight to the
     * database, so schema changes go through the migrator instead, which also gives each addon
     * a working rollback.
     */
    private function runAddonMigrations(string $addonName): void
    {
        $migrationPath = $this->addonMigrationPath($addonName);

        if (!$migrationPath) {
            return;
        }

        $exitCode = Artisan::call('migrate', [
            '--path'     => $migrationPath,
            '--realpath' => true,
            '--force'    => true,
        ]);

        if ($exitCode !== 0) {
            throw new \RuntimeException('The addon database migration failed: ' . trim(Artisan::output()));
        }
    }

    /**
     * Reverse an addon's migrations. Used when an install fails part way through.
     */
    private function rollbackAddonMigrations(string $addonName): void
    {
        $migrationPath = $this->addonMigrationPath($addonName);

        if (!$migrationPath) {
            return;
        }

        try {
            Artisan::call('migrate:rollback', [
                '--path'     => $migrationPath,
                '--realpath' => true,
                '--force'    => true,
            ]);
        } catch (\Throwable $e) {
            // A failed rollback must not mask the original installation error.
        }
    }

    private function addonMigrationPath(string $addonName): ?string
    {
        $path = base_path('addons/' . $addonName . '/Source/database/migrations');

        return is_dir($path) ? $path : null;
    }

    public function versionUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'addon_id'   => 'required|integer|exists:installed_addons,id',
            'update_zip' => 'required|file|mimes:zip',
        ]);

        if ($validator->fails()) {
            return apiResponse('Validation failed', 'error', $validator->errors()->all(), [], 422);
        }

        $addon = InstalledAddon::find($request->addon_id);

        if (!$addon || !$addon->update_available) {
            return apiResponse('Update unavailable', 'error', ['There is no pending update for this addon.'], [], 422);
        }

        try {
            if (!extension_loaded('zip')) {
                throw new \Exception('The PHP Zip extension is required to verify addon updates.');
            }

            $meta = $this->readAddonJson($request->file('update_zip')->getRealPath(), ['slug', 'version']);

            $verification = $this->verifyAddonPurchase(
                trim($addon->purchase_code),
                trim($addon->envato_username),
                $meta['slug'],
                true
            );

            if ($verification['status'] == 'error') {
                return apiResponse('error', 'error', [$verification['message']]);
            }

            if ((string) $meta['slug'] !== (string) $addon->slug) {
                return apiResponse('Invalid addon package', 'error', ['This update file is for a different addon. Please upload the package for ' . $addon->name . '.'], [], 422);
            }

            if ((string) $meta['version'] !== (string) $addon->update_available) {
                return apiResponse('Invalid addon version', 'error', ['Version ' . $addon->update_available . ' is required, but the uploaded package contains version ' . $meta['version'] . '.'], [], 422);
            }

            $this->mergeAddonUpdate($request->file('update_zip')->getRealPath(), $addon->name);

            $this->runAddonMigrations($addon->name);

            $addon->version          = $meta['version'];
            $addon->update_available = null;
            $addon->save();

            Cache::forget('installed_addons_active');
            app(PermissionRegistrar::class)->forgetCachedPermissions();
            Artisan::call('optimize:clear');

            if (function_exists('opcache_reset')) {
                @opcache_reset();
            }

            return apiResponse('Addon updated', 'success', ['The ' . $addon->name . ' addon was updated to v' . $meta['version'] . ' successfully.']);
        } catch (\Throwable $e) {
            return apiResponse('Addon update failed', 'error', [$e->getMessage() ?: 'The addon could not be updated.'], [], 422);
        }
    }

    private function verifyAddonPurchase(string $purchaseCode, string $envatoUsername, string $slug, $update = false): ?array
    {
        $url = 'https://license.ovosolution.com/addon-verify';
        if($update){
            $url = 'https://license.ovosolution.com/addon-update-verify';
        }
        $response = Http::post(
            $url,
            [
                'purchase_code'   => $purchaseCode,
                'envato_username' => $envatoUsername,
                'addon_slug'      => $slug,
            ]
        );

        $data = $response->json();
        if ($response->failed()) {
            return [
                'status'  => 'error',
                'message' => @$data['message'] ?? 'Something went to wrong, please try again later.'
            ];
        }

        if ($data['success'] == false && !$data['errors']) {
            return [
                'status'  => 'error',
                'message' => $data['message'] ?? 'Something went to wrong, please try again later.'
            ];
        }

        return [
            'status'  => 'success',
            'message' => @$data['message'] ?? 'The addon was successfully verified.'
        ];

    }

    private function mergeAddonUpdate(string $zipPath, string $name): void
    {
        $destination = base_path('addons/' . $name);

        if (!is_dir($destination)) {
            throw new \Exception('The installed addon directory could not be found.');
        }

        if (!is_writable($destination)) {
            throw new \Exception('The installed addon directory is not writable. Please check its permissions.');
        }

        $zip = new ZipArchive();

        if ($zip->open($zipPath) !== true) {
            throw new \Exception('Could not open ZIP file.');
        }

        try {
            if (!$zip->extractTo($destination)) {
                throw new \Exception('The addon update files could not be extracted.');
            }
        } finally {
            $zip->close();
        }
    }

    private function readAddonJson(string $zipPath, array $requiredFields = ['name', 'title', 'slug', 'version', 'author', 'description', 'provider']): ?array
    {

        $zip = new ZipArchive();

        if ($zip->open($zipPath) !== true) {
            throw new \Exception('Could not open ZIP file.');
        }

        $json = null;

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if (basename($name) === 'config.json') {
                $json = json_decode($zip->getFromIndex($i), true);
                break;
            }
        }

        $zip->close();

        if (!$json || !is_array($json) || array_diff($requiredFields, array_keys($json))) {
            @unlink($zipPath);
            throw new \Exception('Invalid addon package. The config.json is not found.');
        }
        return $json;
    }

    private function extractZip(string $zipPath, string $name): string
    {

        $zip = new ZipArchive();

        if ($zip->open($zipPath) !== true) {
            throw new \Exception('Could not open ZIP file.');
        }

        $destination = base_path('addons/' . $name);

        if (is_dir($destination)) {
            $this->deleteDirectory($destination);
        }

        $zip->extractTo($destination);
        $zip->close();

        return $destination;
    }

    private function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) return;

        $items = array_diff(scandir($dir), ['.', '..']);

        foreach ($items as $item) {
            $path = $dir . '/' . $item;
            is_dir($path) ? $this->deleteDirectory($path) : unlink($path);
        }
        rmdir($dir);
    }

    private function checkPHPAndServerRequirements(): void
    {
        if (!extension_loaded('zip')) {
            throw new \Exception('The PHP Zip extension is required to install addons. Please enable it in your php.ini or contact your hosting provider to enable it.');
        }

        if (!extension_loaded('fileinfo')) {
            throw new \Exception('The PHP Fileinfo extension is required to install addons. Please enable it in your php.ini or contact your hosting provider to enable it.');
        }

        if (!extension_loaded('curl')) {
            throw new \Exception('The PHP cURL extension is required to install addons. Please enable it in your php.ini or contact your hosting provider to enable it.');
        }

        if (!extension_loaded('openssl')) {
            throw new \Exception('The PHP OpenSSL extension is required to install addons. Please enable it in your php.ini or contact your hosting provider to enable it.');
        }

        if (!extension_loaded('json')) {
            throw new \Exception('The PHP JSON extension is required to install addons. Please enable it in your php.ini or contact your hosting provider to enable it.');
        }


        $tmpPath = storage_path('app/temp/addon');

        if (!is_dir($tmpPath)) {
            mkdir($tmpPath, 0755, true);
        }

        if (!is_writable($tmpPath)) {
            throw new \Exception('The storage/app/temp/addon directory is not writable. Please set the correct permissions (755) or contact your hosting provider.');
        }

        if (!is_writable(base_path('addons'))) {
            throw new \Exception('The addons directory is not writable. Please set the correct permissions (755) or contact your hosting provider.');
        }
    }

    public function toggle(int $id)
    {
        $addon = InstalledAddon::findOrFail($id);

        if ($addon->status == Status::ADDON_INSTALLED) {
            $addon->status = Status::ADDON_UNINSTALLED;
            $message       = "Addon uninstalled successfully!";
        } else {
            $addon->status = Status::ADDON_INSTALLED;
            $message       = "Addon installed successfully!";
        }

        Cache::forget('installed_addons_active');
        $addon->save();
        $notify[] = ['success', $message];
        return back()->withNotify($notify);
    }
}
