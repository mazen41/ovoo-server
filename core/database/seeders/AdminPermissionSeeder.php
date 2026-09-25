<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class AdminPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            "manage user" => [
                "view users",
                "view user agents",
                "send user notification",
                "view user notifications",
                "update user balance",
                "ban user",
                "login as user",
                "update user"
            ],
            "pricing plan" => [
                "view pricing plans",
                "add pricing plan",
                "edit pricing plan"
            ],
            "system data" => [
                "view contact",
                "view contact list",
                "view contact tag",
                "view campaign",
                "view short link"
            ],
            "deposit" => [
                "view deposit",
                "approve deposit",
                "reject deposit",
            ],
            "withdraw" => [
                "view withdraw",
                "approve withdraw",
                "reject withdraw",
            ],
            "admin" => [
                "view admin",
                "add admin",
                "edit admin"
            ],
            "role" => [
                "view roles",
                "add role",
                "edit role",
                "assign permissions"
            ],
            "gateway" => [
                "manage gateways",
                "manage withdraw methods"
            ],
            "coupon" => [
                "view coupon",
                "add coupon",
                "edit coupon",
            ],
            "setting" => [
                "update general settings",
                "ai assistant settings",
                "update brand settings",
                "system configuration",
                "pusher configuration",
                "notification settings",
                "kyc settings",
                "update maintenance mode",
                "social login settings",
                "seo settings",
                "in app payment settings",
            ],
            "report" => [
                "view all transactions",
                "view user transactions",
                "view login history",
                "view subscription history",
                "view all notifications"
            ],
            "support ticket" => [
                "view tickets",
                "answer tickets",
                "close tickets"
            ],
            "manage content" => [
                "manage pages",
                "manage sections"
            ],
            "other" => [
                "view dashboard",
                "manage extensions",
                "manage languages",
                "manage subscribers",
                "view application info",
                "custom css",
                "manage cron job",
                "sitemap xml",
                "robots txt",
                "cookie settings",
            ]
        ];

        foreach ($permissions as $k => $permission) {
            foreach ($permission as  $item) {
                $exists = Permission::where("name", $item)->where('group_name', $k)->exists();
                if ($exists) continue;
                $permission             = new Permission();
                $permission->name       = $item;
                $permission->group_name = $k;
                $permission->guard_name = "admin";
                $permission->save();
            }
        }

    }
}
