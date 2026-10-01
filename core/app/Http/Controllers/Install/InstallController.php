<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use App\Lib\Installer;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

/**
 * The setup wizard.
 *
 * Four steps: check the server, verify the purchase, connect the database, then build it and
 * create the first admin. Progress is carried in the session, and the whole thing is closed off
 * by App\Http\Middleware\RedirectIfInstalled once it finishes.
 */
class InstallController extends Controller
{
    public function requirements()
    {
        $check = Installer::checkRequirements();

        return view('install.requirements', [
            'pageTitle' => 'Server Requirements',
            'step'      => 1,
            'rows'      => $check['rows'],
            'passed'    => $check['passed'],
        ]);
    }

    public function purchase()
    {
        if (!config('install.verify_purchase')) {
            session(['install.purchase_verified' => true]);
            return to_route('install.database');
        }

        return view('install.purchase', [
            'pageTitle'     => 'Verify Purchase',
            'step'          => 2,
            'back'          => route('install.requirements'),
            'savedUsername' => session('install.envato_username'),
        ]);
    }

    public function verifyPurchase(Request $request)
    {
        $request->validate([
            'purchase_code'   => 'required|string|max:191',
            'envato_username' => 'required|string|max:191',
        ]);

        $result = Installer::verifyPurchase(
            trim($request->purchase_code),
            trim($request->envato_username)
        );

        if (!$result['verified']) {
            return back()->withInput($request->except('purchase_code'))
                ->withErrors(['purchase_code' => $result['message']]);
        }


        session([
            'install.purchase_verified' => true,
            // Kept for the .env write in the final step so addon installs can reuse it.
            'install.purchase_code'     => trim($request->purchase_code),
            'install.envato_username'   => trim($request->envato_username),
        ]);

        return to_route('install.database');
    }

    public function database()
    {
        if (config('install.verify_purchase') && !session('install.purchase_verified')) {
            return to_route('install.purchase');
        }

        return view('install.database', [
            'pageTitle' => 'Database Connection',
            'step'      => 3,
            // Purchase verification can be switched off, in which case step 2 never renders and
            // Back has to skip over it.
            'back'      => config('install.verify_purchase')
                ? route('install.purchase')
                : route('install.requirements'),
            // Everything but the password, so returning from step 4 does not mean retyping it all.
            'saved'     => collect(session('install.database', []))->except('password')->all(),
        ]);
    }

    public function saveDatabase(Request $request)
    {
        if (config('install.verify_purchase') && !session('install.purchase_verified')) {
            return to_route('install.purchase');
        }

        $request->validate([
            'db_host'     => 'required|string|max:191',
            'db_port'     => 'required|numeric',
            'db_database' => 'required|string|max:191',
            'db_username' => 'required|string|max:191',
            'db_password' => 'nullable|string|max:191',
        ]);

        $credentials = [
            'host'     => $request->db_host,
            'port'     => $request->db_port,
            'database' => $request->db_database,
            'username' => $request->db_username,
            'password' => (string) $request->db_password,
        ];

        $test = Installer::testDatabase($credentials);

        if (!$test['connected']) {
            return back()->withInput($request->except('db_password'))
                ->withErrors(['db_host' => 'Could not connect: ' . $test['message']]);
        }

        session(['install.database' => $credentials]);

        return to_route('install.account');
    }

    public function account()
    {
        if (!session('install.database')) {
            return to_route('install.database');
        }

        return view('install.account', [
            'pageTitle' => 'Administrator Account',
            'step'      => 4,
            'back'      => route('install.database'),
        ]);
    }

    public function finish(Request $request)
    {
        $credentials = session('install.database');

        if (!$credentials) {
            return to_route('install.database');
        }

        $request->validate([
            'name'     => 'required|string|max:80',
            'email'    => 'required|email|max:191',
            'username' => 'required|string|min:4|max:40|alpha_dash',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        try {
            // .env first, so a failure part way through still leaves a usable configuration.
            Installer::writeEnv(array_filter([
                'APP_URL'         => rtrim(url('/'), '/'),
                'DB_HOST'         => $credentials['host'],
                'DB_PORT'         => $credentials['port'],
                'DB_DATABASE'     => $credentials['database'],
                'DB_USERNAME'     => $credentials['username'],
                'DB_PASSWORD'     => $credentials['password'],
                'PURCHASE_CODE'   => session('install.purchase_code', ''),
                'ENVATO_USERNAME' => session('install.envato_username', ''),
            ], fn ($value) => $value !== null));

            // bootstrap/app.php already gives every installation its own key on first run, so
            // this only covers a hand-made .env that has none. Regenerating unconditionally would
            // just invalidate the session of whoever is running the wizard.
            if (!config('app.key')) {
                Artisan::call('key:generate', ['--force' => true]);
            }

            Installer::useDatabase($credentials);

            Artisan::call('migrate', ['--seed' => true, '--force' => true]);

            $this->createAdmin($request);

            Installer::lock();

            Artisan::call('optimize:clear');
        } catch (\Throwable $e) {
            return back()->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['name' => 'Installation failed: ' . $e->getMessage()]);
        }

        session()->forget('install');

        return view('install.complete', [
            'pageTitle' => 'Installation Complete',
            'step'      => 5,
        ]);
    }

    /**
     * The seeded data ships a placeholder administrator, so the account created here replaces it
     * rather than sitting alongside it.
     */
    private function createAdmin(Request $request): void
    {
        DB::transaction(function () use ($request) {
            Admin::query()->delete();

            $admin           = new Admin();
            $admin->name     = $request->name;
            $admin->email    = $request->email;
            $admin->username = $request->username;
            $admin->password = bcrypt($request->password);
            $admin->save();

            if (method_exists($admin, 'assignRole') && DB::table('roles')->where('id', 1)->exists()) {
                $admin->assignRole(DB::table('roles')->where('id', 1)->value('name'));
            }
        });
    }
}
