<?php

namespace Database\Seeders\Install;

/**
 * Reference data for the cron_schedules table, generated from install/database.sql.
 *
 * Primary keys are written explicitly because other seeded tables reference them by id.
 */
class CronSchedulesSeeder extends InstallSeeder
{
    public function run(): void
    {
        $this->insertRows('cron_schedules', [
            ['id' => '1', 'name' => 'Hourly', 'interval' => '3600', 'status' => '1', 'created_at' => '2024-03-13 23:34:09', 'updated_at' => '2025-02-27 05:54:21'],
            ['id' => '3', 'name' => 'Daily', 'interval' => '86400', 'status' => '1', 'created_at' => '2024-05-06 04:46:39', 'updated_at' => '2024-05-06 04:46:39'],
            ['id' => '4', 'name' => 'Yearly', 'interval' => '31622400', 'status' => '1', 'created_at' => '2024-09-09 02:52:56', 'updated_at' => '2025-02-27 05:55:15'],
        ]);
    }
}
