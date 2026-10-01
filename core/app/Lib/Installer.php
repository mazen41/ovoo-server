<?php

namespace App\Lib;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use PDO;

/**
 * Everything the setup wizard needs to do its job.
 *
 * Kept apart from the controller so the whole installer is five small files that can be copied
 * into another project unchanged, with only config/install.php edited.
 */
class Installer
{
    /**
     * Has this copy already been set up?
     *
     */
    public static function isInstalled(): bool
    {
        if (file_exists(self::lockPath())) {
            return true;
        }

        try {
            if (Schema::hasTable('general_settings') && DB::table('general_settings')->exists()) {
                self::lock('detected an existing installation');
                return true;
            }
        } catch (\Throwable $e) {
            // No usable database yet, so this is a fresh copy.
        }

        return false;
    }

    /**
     * URL of the wizard's logo, or null to fall back to the product name.
     *
     * Two candidates because this project serves its public files from the repository root rather
     * than core/public, while a stock Laravel copy of the installer would use public_path().
     */
    public static function logoUrl(): ?string
    {
        $relative = config('install.logo');

        if (!$relative) {
            return null;
        }

        foreach ([base_path('../' . $relative), public_path($relative)] as $candidate) {
            if (is_file($candidate)) {
                return url($relative);
            }
        }

        return null;
    }

    public static function lockPath(): string
    {
        return storage_path(config('install.lock_file', 'installed'));
    }

    public static function lock(string $note = ''): void
    {
        @file_put_contents(self::lockPath(), implode("\n", [
            'Installed: ' . date('Y-m-d H:i:s'),
            'Product: ' . config('install.product_name'),
            $note ? 'Note: ' . $note : '',
            '',
            'Deleting this file reopens the setup wizard and allows anyone to reconfigure this',
            'installation. Keep it in place.',
        ]));
    }

    /**
     * Check the server against config/install.php. Returns a list of rows for the view plus
     * whether everything passed.
     */
    public static function checkRequirements(): array
    {
        $requirements = config('install.requirements');
        $rows         = [];
        $passed       = true;

        $phpOk = version_compare(PHP_VERSION, $requirements['php'], '>=');
        $rows[] = [
            'group'    => 'PHP',
            'label'    => 'PHP ' . $requirements['php'] . ' or newer',
            'current'  => PHP_VERSION,
            'passed'   => $phpOk,
        ];
        $passed = $passed && $phpOk;

        foreach ($requirements['extensions'] as $extension) {
            $ok     = extension_loaded($extension);
            $passed = $passed && $ok;
            $rows[] = [
                'group'   => 'Extension',
                'label'   => $extension,
                'current' => $ok ? 'Loaded' : 'Missing',
                'passed'  => $ok,
            ];
        }

        foreach ($requirements['writable'] as $relative) {
            $path = base_path($relative);
            $ok   = file_exists($path) ? is_writable($path) : is_writable(dirname($path));

            $passed = $passed && $ok;
            $rows[] = [
                'group'   => 'Permission',
                'label'   => $relative,
                'current' => $ok ? 'Writable' : 'Not writable',
                'passed'  => $ok,
            ];
        }

        return ['rows' => $rows, 'passed' => $passed];
    }

    /**
     * Ask the license server whether this purchase code is genuine.
     *
     */
    public static function verifyPurchase(string $purchaseCode, string $envatoUsername): array
    {
        try {
            $domain = url('/');
            $ip = request()->ip();
            $securityData = [
                'php_version' => PHP_VERSION,
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? null,
                'installed_at' => date('Y-m-d H:i:s'),
            ];

            $response = Http::timeout(20)->post(config('install.verify_url'), [
                'purchase_code'   => $purchaseCode,
                'envato_username' => $envatoUsername,
                'username'        => $envatoUsername,
                'product_slug'    => config('install.product_slug'),
                'domain'          => $domain,
                'ip_address'      => $ip,
                'security_data'   => $securityData,
            ]);
        } catch (\Throwable $e) {

            return [
                'verified' => false,
                'message'  => 'The license server could not be reached. Check the server\'s internet connection and try again.',
            ];
        }

        $data = $response->json();

        if ($response->failed() || !is_array($data)) {
            return [
                'verified' => false,
                'message'  => $data['message'] ?? 'The purchase could not be verified. Please try again later.',
            ];
        }

        if (empty($data['success'])) {
            return [
                'verified' => false,
                'message'  => $data['message'] ?? 'This purchase code is not valid for this product.',
            ];
        }

        return [
            'verified' => true,
            'message'  => $data['message'] ?? 'Purchase verified.',
        ];
    }

    /**
     * Try the supplied credentials before anything is written to disk, so a typo cannot leave
     * the application pointing at a database that does not exist.
     */
    public static function testDatabase(array $credentials): array
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s',
            $credentials['host'],
            $credentials['port'],
            $credentials['database']
        );

        try {
            new PDO($dsn, $credentials['username'], $credentials['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 10,
            ]);
        } catch (\PDOException $e) {
            return ['connected' => false, 'message' => $e->getMessage()];
        }

        return ['connected' => true, 'message' => 'Connected.'];
    }

    /**
     * Point the running process at the new database.
     *
     */
    public static function useDatabase(array $credentials): void
    {
        config([
            'database.connections.mysql.host'     => $credentials['host'],
            'database.connections.mysql.port'     => $credentials['port'],
            'database.connections.mysql.database' => $credentials['database'],
            'database.connections.mysql.username' => $credentials['username'],
            'database.connections.mysql.password' => $credentials['password'],
        ]);

        DB::purge('mysql');
        DB::reconnect('mysql');
    }

    /**
     * Merge values into .env, creating it from .env.example when it does not exist yet.
     */
    public static function writeEnv(array $values): void
    {
        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            $example = base_path('.env.example');

            if (!file_exists($example)) {
                throw new \RuntimeException('Neither .env nor .env.example is present. Re-upload the package.');
            }

            copy($example, $envPath);
        }

        $contents = file_get_contents($envPath);

        foreach ($values as $key => $value) {
            $line = $key . '=' . self::escapeEnvValue((string) $value);

            if (preg_match('/^' . preg_quote($key, '/') . '=.*$/m', $contents)) {
                $contents = preg_replace('/^' . preg_quote($key, '/') . '=.*$/m', $line, $contents);
            } else {
                $contents = rtrim($contents, "\r\n") . "\n" . $line . "\n";
            }
        }

        file_put_contents($envPath, $contents);
    }

    /**
     * A value with a newline in it could otherwise introduce extra variables, and one with a
     * space or a quote would be misread, so anything unusual is quoted and escaped.
     */
    private static function escapeEnvValue(string $value): string
    {
        $value = str_replace(["\r", "\n"], '', $value);

        if ($value === '' || preg_match('/^[A-Za-z0-9_.\-\/:]+$/', $value)) {
            return $value;
        }

        return '"' . str_replace(['\\', '"', '$'], ['\\\\', '\\"', '\\$'], $value) . '"';
    }
}
