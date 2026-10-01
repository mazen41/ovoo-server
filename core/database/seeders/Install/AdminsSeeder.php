<?php

namespace Database\Seeders\Install;

/**
 * Reference data for the admins table, generated from install/database.sql.
 *
 * Primary keys are written explicitly because other seeded tables reference them by id.
 */
class AdminsSeeder extends InstallSeeder
{
    public function run(): void
    {
        $this->insertRows('admins', [
            ['id' => '1', 'name' => 'Super Admin', 'email' => 'admin@site.com', 'username' => 'admin', 'email_verified_at' => null, 'image' => '670ce58c687511728898444.png', 'password' => '$2y$12$ecxM9ta/Mu9RTovy4/xAKebotQbkFcTwDEriRGnf3bwwJ2YBn//Ai', 'remember_token' => null, 'status' => '1', 'created_at' => '2024-09-01 11:37:12', 'updated_at' => '2024-10-14 03:34:04'],
        ]);
    }
}
