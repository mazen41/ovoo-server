<?php

namespace Database\Seeders\Install;

/**
 * Reference data for the permissions table, generated from install/database.sql.
 *
 * Primary keys are written explicitly because other seeded tables reference them by id.
 */
class PermissionsSeeder extends InstallSeeder
{
    public function run(): void
    {
        $this->insertRows('permissions', [
            ['id' => '1', 'name' => 'view users', 'guard_name' => 'admin', 'group_name' => 'manage user', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '2', 'name' => 'view user agents', 'guard_name' => 'admin', 'group_name' => 'manage user', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '3', 'name' => 'send user notification', 'guard_name' => 'admin', 'group_name' => 'manage user', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '4', 'name' => 'view user notifications', 'guard_name' => 'admin', 'group_name' => 'manage user', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '5', 'name' => 'update user balance', 'guard_name' => 'admin', 'group_name' => 'manage user', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '6', 'name' => 'ban user', 'guard_name' => 'admin', 'group_name' => 'manage user', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '7', 'name' => 'login as user', 'guard_name' => 'admin', 'group_name' => 'manage user', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '8', 'name' => 'update user', 'guard_name' => 'admin', 'group_name' => 'manage user', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '9', 'name' => 'view pricing plans', 'guard_name' => 'admin', 'group_name' => 'pricing plan', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '10', 'name' => 'add pricing plan', 'guard_name' => 'admin', 'group_name' => 'pricing plan', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '11', 'name' => 'edit pricing plan', 'guard_name' => 'admin', 'group_name' => 'pricing plan', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '12', 'name' => 'view contact', 'guard_name' => 'admin', 'group_name' => 'system data', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '13', 'name' => 'view contact list', 'guard_name' => 'admin', 'group_name' => 'system data', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '14', 'name' => 'view contact tag', 'guard_name' => 'admin', 'group_name' => 'system data', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '15', 'name' => 'view campaign', 'guard_name' => 'admin', 'group_name' => 'system data', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '16', 'name' => 'view chatbot', 'guard_name' => 'admin', 'group_name' => 'system data', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '17', 'name' => 'view short link', 'guard_name' => 'admin', 'group_name' => 'system data', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '18', 'name' => 'view deposit', 'guard_name' => 'admin', 'group_name' => 'deposit', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '19', 'name' => 'approve deposit', 'guard_name' => 'admin', 'group_name' => 'deposit', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '20', 'name' => 'reject deposit', 'guard_name' => 'admin', 'group_name' => 'deposit', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '21', 'name' => 'view withdraw', 'guard_name' => 'admin', 'group_name' => 'withdraw', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '22', 'name' => 'approve withdraw', 'guard_name' => 'admin', 'group_name' => 'withdraw', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '23', 'name' => 'reject withdraw', 'guard_name' => 'admin', 'group_name' => 'withdraw', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '24', 'name' => 'view admin', 'guard_name' => 'admin', 'group_name' => 'admin', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '25', 'name' => 'add admin', 'guard_name' => 'admin', 'group_name' => 'admin', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '26', 'name' => 'edit admin', 'guard_name' => 'admin', 'group_name' => 'admin', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '27', 'name' => 'view roles', 'guard_name' => 'admin', 'group_name' => 'role', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '28', 'name' => 'add role', 'guard_name' => 'admin', 'group_name' => 'role', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '29', 'name' => 'edit role', 'guard_name' => 'admin', 'group_name' => 'role', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '30', 'name' => 'assign permissions', 'guard_name' => 'admin', 'group_name' => 'role', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '31', 'name' => 'manage gateways', 'guard_name' => 'admin', 'group_name' => 'gateway', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '32', 'name' => 'manage withdraw methods', 'guard_name' => 'admin', 'group_name' => 'gateway', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '33', 'name' => 'update general settings', 'guard_name' => 'admin', 'group_name' => 'setting', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '34', 'name' => 'update brand settings', 'guard_name' => 'admin', 'group_name' => 'setting', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '35', 'name' => 'system configuration', 'guard_name' => 'admin', 'group_name' => 'setting', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '36', 'name' => 'pusher configuration', 'guard_name' => 'admin', 'group_name' => 'setting', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '37', 'name' => 'notification settings', 'guard_name' => 'admin', 'group_name' => 'setting', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '38', 'name' => 'kyc settings', 'guard_name' => 'admin', 'group_name' => 'setting', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '39', 'name' => 'update maintenance mode', 'guard_name' => 'admin', 'group_name' => 'setting', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '40', 'name' => 'social login settings', 'guard_name' => 'admin', 'group_name' => 'setting', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '41', 'name' => 'seo settings', 'guard_name' => 'admin', 'group_name' => 'setting', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '42', 'name' => 'in app payment settings', 'guard_name' => 'admin', 'group_name' => 'setting', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '43', 'name' => 'view all transactions', 'guard_name' => 'admin', 'group_name' => 'report', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '44', 'name' => 'view user transactions', 'guard_name' => 'admin', 'group_name' => 'report', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '45', 'name' => 'view login history', 'guard_name' => 'admin', 'group_name' => 'report', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '46', 'name' => 'view subscription history', 'guard_name' => 'admin', 'group_name' => 'report', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '47', 'name' => 'view all notifications', 'guard_name' => 'admin', 'group_name' => 'report', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '48', 'name' => 'view tickets', 'guard_name' => 'admin', 'group_name' => 'support ticket', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '49', 'name' => 'answer tickets', 'guard_name' => 'admin', 'group_name' => 'support ticket', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '50', 'name' => 'close tickets', 'guard_name' => 'admin', 'group_name' => 'support ticket', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '51', 'name' => 'manage pages', 'guard_name' => 'admin', 'group_name' => 'manage content', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '52', 'name' => 'manage sections', 'guard_name' => 'admin', 'group_name' => 'manage content', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '53', 'name' => 'view dashboard', 'guard_name' => 'admin', 'group_name' => 'other', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '54', 'name' => 'manage extensions', 'guard_name' => 'admin', 'group_name' => 'other', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '55', 'name' => 'manage languages', 'guard_name' => 'admin', 'group_name' => 'other', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '56', 'name' => 'manage subscribers', 'guard_name' => 'admin', 'group_name' => 'other', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '57', 'name' => 'view application info', 'guard_name' => 'admin', 'group_name' => 'other', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '58', 'name' => 'custom css', 'guard_name' => 'admin', 'group_name' => 'other', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '59', 'name' => 'manage cron job', 'guard_name' => 'admin', 'group_name' => 'other', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '60', 'name' => 'sitemap xml', 'guard_name' => 'admin', 'group_name' => 'other', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '61', 'name' => 'robots txt', 'guard_name' => 'admin', 'group_name' => 'other', 'created_at' => '2025-08-18 14:14:01', 'updated_at' => '2025-08-18 14:14:01'],
            ['id' => '65', 'name' => 'add coupon', 'guard_name' => 'admin', 'group_name' => 'coupon', 'created_at' => '2025-08-24 07:37:14', 'updated_at' => '2025-08-24 07:37:14'],
            ['id' => '66', 'name' => 'edit coupon', 'guard_name' => 'admin', 'group_name' => 'coupon', 'created_at' => '2025-08-24 07:37:14', 'updated_at' => '2025-08-24 07:37:14'],
            ['id' => '67', 'name' => 'cookie settings', 'guard_name' => 'admin', 'group_name' => 'other', 'created_at' => '2025-08-24 07:37:14', 'updated_at' => '2025-08-24 07:37:14'],
            ['id' => '68', 'name' => 'view coupon', 'guard_name' => 'admin', 'group_name' => 'coupon', 'created_at' => '2025-08-24 07:37:46', 'updated_at' => '2025-08-24 07:37:46'],
            ['id' => '69', 'name' => 'ai assistant settings', 'guard_name' => 'admin', 'group_name' => 'setting', 'created_at' => null, 'updated_at' => null],
            ['id' => '70', 'name' => 'pwa settings', 'guard_name' => 'admin', 'group_name' => 'setting', 'created_at' => null, 'updated_at' => null],
            ['id' => '71', 'name' => 'manage addons', 'guard_name' => 'admin', 'group_name' => 'other', 'created_at' => '2026-05-22 09:22:00', 'updated_at' => '2026-05-22 09:22:07'],
        ]);
    }
}
