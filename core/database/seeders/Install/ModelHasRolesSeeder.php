<?php

namespace Database\Seeders\Install;

/**
 * Reference data for the model_has_roles table, generated from install/database.sql.
 *
 * Primary keys are written explicitly because other seeded tables reference them by id.
 */
class ModelHasRolesSeeder extends InstallSeeder
{
    public function run(): void
    {
        $this->insertRows('model_has_roles', [
            ['role_id' => '1', 'model_type' => 'App\\Models\\Admin', 'model_id' => '1'],
        ]);
    }
}
