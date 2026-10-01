<?php

namespace Database\Seeders\Install;

/**
 * Reference data for the cron_jobs table, generated from install/database.sql.
 *
 * Primary keys are written explicitly because other seeded tables reference them by id.
 */
class CronJobsSeeder extends InstallSeeder
{
    public function run(): void
    {
        // The coexistence row is also inserted by the add_coexistence_settings_and_cron
        // migration, which runs first on a fresh install. `alias` carries no unique index,
        // so insertOrIgnore would not catch that and the job would fire twice per tick.
        $existingAliases = \Illuminate\Support\Facades\DB::table('cron_jobs')->pluck('alias')->all();

        $rows = [
            ['id' => '6', 'name' => 'Subscription Expiration Notify', 'alias' => 'subscription_expired', 'action' => '["\\\\App\\\\Http\\\\Controllers\\\\CronController","subscriptionExpired"]', 'url' => '', 'cron_schedule_id' => '3', 'next_run' => '2025-05-31 17:53:08', 'last_run' => '2025-05-30 17:53:08', 'is_running' => '1', 'is_default' => '1', 'created_at' => '2024-09-09 03:36:44', 'updated_at' => '2025-05-30 11:53:08'],
            ['id' => '7', 'name' => 'Subscription notify ', 'alias' => 'subscription_notify', 'action' => '["\\\\App\\\\Http\\\\Controllers\\\\CronController","subscriptionNotify"]', 'url' => '', 'cron_schedule_id' => '3', 'next_run' => '2025-05-31 17:55:26', 'last_run' => '2025-05-30 17:55:26', 'is_running' => '1', 'is_default' => '1', 'created_at' => '2024-09-09 03:36:44', 'updated_at' => '2025-05-30 11:55:26'],
            ['id' => '8', 'name' => 'Send Campaign Message', 'alias' => 'campaign_message', 'action' => '["\\\\App\\\\Http\\\\Controllers\\\\CronController","campaignMessage"]', 'url' => '', 'cron_schedule_id' => '3', 'next_run' => '2025-04-15 14:43:43', 'last_run' => '2025-04-14 14:43:43', 'is_running' => '1', 'is_default' => '1', 'created_at' => '2024-09-09 03:36:44', 'updated_at' => '2025-04-14 08:43:43'],
            ['id' => '9', 'name' => 'Coupon Expiration', 'alias' => 'coupon_expiration', 'action' => '["\\\\App\\\\Http\\\\Controllers\\\\CronController","couponExpiration"]', 'url' => '', 'cron_schedule_id' => '3', 'next_run' => '2025-04-15 14:43:43', 'last_run' => '2025-04-14 14:43:43', 'is_running' => '1', 'is_default' => '1', 'created_at' => '2024-09-09 03:36:44', 'updated_at' => '2025-04-14 08:43:43'],
            ['id' => '10', 'name' => 'Clear Trash Media', 'alias' => 'clear_trash_media', 'action' => '["\\\\App\\\\Http\\\\Controllers\\\\CronController","clearTrashMedia"]', 'url' => '', 'cron_schedule_id' => '3', 'next_run' => '2025-11-09 13:00:34', 'last_run' => '2025-11-08 13:00:34', 'is_running' => '1', 'is_default' => '1', 'created_at' => '2024-09-09 09:36:44', 'updated_at' => '2025-11-08 13:00:34'],
            ['id' => '11', 'name' => 'Change Campaign STtaus', 'alias' => 'change_campaign_status', 'action' => '["\\\\App\\\\Http\\\\Controllers\\\\CronController","checkCampaignStatus"]', 'url' => '', 'cron_schedule_id' => '4', 'next_run' => '2025-12-22 14:04:03', 'last_run' => '2025-12-21 14:04:03', 'is_running' => '1', 'is_default' => '1', 'created_at' => '2024-09-09 09:36:44', 'updated_at' => '2025-12-21 20:04:03'],
            ['id' => '12', 'name' => 'Update Campaign Message', 'alias' => 'update_campaign_message', 'action' => '["\\\\App\\\\Http\\\\Controllers\\\\CronController","campaignMessageUpdate"]', 'url' => '', 'cron_schedule_id' => '3', 'next_run' => '2026-02-14 19:39:03', 'last_run' => '2026-02-13 19:39:03', 'is_running' => '1', 'is_default' => '1', 'created_at' => '2024-09-09 09:36:44', 'updated_at' => '2026-02-14 01:39:03'],
            ['id' => '13', 'name' => 'Addon Version Update', 'alias' => 'addon_version_update', 'action' => '["\\\\App\\\\Http\\\\Controllers\\\\CronController","checkVersionUpdate"]', 'url' => '', 'cron_schedule_id' => '3', 'next_run' => '2026-07-23 14:50:13', 'last_run' => '2026-07-22 14:50:13', 'is_running' => '1', 'is_default' => '1', 'created_at' => null, 'updated_at' => null],
            ['id' => '14', 'name' => 'Coexistence Media Sync', 'alias' => 'coexistence_media_sync', 'action' => '["\\\\App\\\\Http\\\\Controllers\\\\CronController","coexistenceMediaSync"]', 'url' => '', 'cron_schedule_id' => '1', 'next_run' => '2026-09-05 00:00:00', 'last_run' => '2026-09-05 00:00:00', 'is_running' => '1', 'is_default' => '1', 'created_at' => null, 'updated_at' => null],
        ];

        $this->insertRows('cron_jobs', array_values(array_filter(
            $rows,
            fn($row) => !in_array($row['alias'], $existingAliases, true)
        )));
    }
}
