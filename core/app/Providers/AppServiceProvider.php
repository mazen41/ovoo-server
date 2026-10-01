<?php

namespace App\Providers;

use App\Lib\Searchable;
use App\Models\InstalledAddon;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Builder::mixin(new Searchable);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Each release ships its migrations in database/migrations/updates/<version>/ so the
        // admin updater can migrate one release in isolation. Laravel only scans the top level
        // of database/migrations, so those folders have to be registered here or a fresh
        // install would never run them.
        $updateMigrationPaths = glob(database_path('migrations/updates/*'), GLOB_ONLYDIR);
        if ($updateMigrationPaths) {
            $this->loadMigrationsFrom($updateMigrationPaths);
        }

        Paginator::useBootstrapFive();

        Builder::macro("firstOrFailWithApi", function ($modelName = "data") {
            $data = $this->first();
            if (!$data) {
                throw new \Exception("custom_not_found_exception || The $modelName is not found", 404);
            }
            return $data;
        });

        Builder::macro("findOrFailWithApi", function ($modelName = "data", $id) {
            $data = $this->where("id", $id)->first();
            if (!$data) {
                throw new \Exception("custom_not_found_exception || The $modelName is not found", 404);
            }
            return $data;
        });

        $this->loadActiveAddons();
    }

    protected function loadActiveAddons(): void
    {

        try {
            InstalledAddon::installed()
                ->get()
                ->each(function ($addon) {
                    $provider = $addon->provider ?? null;
                    if ($provider && class_exists($provider)) {
                        app()->register($provider);
                    }
                });
        } catch (\Exception $e) {
            //skip if DB is  not ready
        }
    }
}
