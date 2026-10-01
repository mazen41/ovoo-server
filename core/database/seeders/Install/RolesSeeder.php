<?php

namespace Database\Seeders\Install;

/**
 * Reference data for the roles table, generated from install/database.sql.
 *
 * Primary keys are written explicitly because other seeded tables reference them by id.
 */
class RolesSeeder extends InstallSeeder
{
    public function run(): void
    {
        $this->insertRows('roles', [
            ['id' => '1', 'name' => 'Super Admin', 'guard_name' => 'admin', 'status' => '1', 'created_at' => '2026-07-22 10:56:56', 'updated_at' => '2026-07-22 10:56:56'],
        ]);
    }
}
