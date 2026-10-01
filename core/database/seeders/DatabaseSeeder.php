<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed a fresh installation.
     *
     * The Install\* seeders carry the same reference data that install/database.sql ships, with
     * primary keys written explicitly so the pivot rows keep pointing at the right records.
     * Every seeder uses insertOrIgnore, so running this more than once is harmless and will not
     * overwrite settings an installation has already customised.
     *
     * The template language list used to be hardcoded here; it now lives in
     * Install\TemplateLanguagesSeeder, generated from the shipped data.
     */
    public function run(): void
    {
        $this->call([
            // Referenced by the pivot tables that follow, so these go first.
            Install\PermissionsSeeder::class,
            Install\RolesSeeder::class,
            Install\RoleHasPermissionsSeeder::class,
            Install\AdminsSeeder::class,
            Install\ModelHasRolesSeeder::class,

            Install\AgentPermissionsSeeder::class,
            Install\AiAssistantsSeeder::class,
            Install\CronSchedulesSeeder::class,
            Install\CronJobsSeeder::class,
            Install\ExtensionsSeeder::class,
            Install\FormsSeeder::class,
            Install\FrontendsSeeder::class,
            Install\GatewaysSeeder::class,
            Install\GatewayCurrenciesSeeder::class,
            Install\GeneralSettingsSeeder::class,
            Install\LanguagesSeeder::class,
            Install\NotificationTemplatesSeeder::class,
            Install\PagesSeeder::class,
            Install\TemplateCategoriesSeeder::class,
            Install\TemplateLanguagesSeeder::class,
        ]);
    }
}
