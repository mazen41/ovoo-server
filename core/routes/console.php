<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

/*
 * Record the shipped migrations as applied without running them.
 *
 * Installations from a release that predates these migrations, whose schema was imported from a
 * SQL dump, already have every table while their migrations table is empty. Plain `php artisan migrate`
 * is safe there (each migration checks for its table first), but it leaves the impression that
 * the schema was just built. This marks them applied instead, so `migrate:status` reflects
 * reality and later updates only run genuinely new migrations.
 *
 * Commands are registered here rather than in app/Console/Commands because bootstrap/app.php
 * passes an explicit commands path to withRouting(), which disables directory auto-discovery.
 */
Artisan::command('ovowpp:baseline-migrations {--dry-run} {--force}', function () {
    if (!Schema::hasTable('migrations')) {
        $this->call('migrate:install');
    }

    $applied = DB::table('migrations')->pluck('migration')->all();
    $batch   = (int) DB::table('migrations')->max('batch');

    $pending = collect(File::files(database_path('migrations')))
        ->map(fn ($file) => $file->getFilenameWithoutExtension())
        ->reject(fn ($name) => in_array($name, $applied, true))
        ->sort()
        ->values();

    if ($pending->isEmpty()) {
        $this->info('Nothing to baseline: every migration is already recorded.');
        return;
    }

    $this->table(['Migration'], $pending->map(fn ($name) => [$name])->all());

    if ($this->option('dry-run')) {
        $this->comment('Dry run, nothing was written.');
        return;
    }

    if (!$this->option('force') && !$this->confirm("Mark {$pending->count()} migration(s) as applied WITHOUT running them?")) {
        return;
    }

    DB::table('migrations')->insert(
        $pending->map(fn ($name) => ['migration' => $name, 'batch' => $batch + 1])->all()
    );

    $this->info("Baselined {$pending->count()} migration(s).");
})->purpose('Record existing migrations as applied, for installs whose schema predates them');
