<?php

namespace Database\Seeders;

use App\Models\AgentPermission;
use Illuminate\Database\Seeder;

/**
 * Authoring tool for agent permissions, run by hand when new permissions are introduced:
 *
 *     php artisan db:seed --class=PermissionSeeder
 *
 * It is deliberately NOT wired into DatabaseSeeder. A fresh installation gets its agent
 * permissions from Install\AgentPermissionsSeeder, which preserves the shipped ids.
 */
class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            "contact" => [
                "view contact",
                "add contact",
                "edit contact",
                "delete contact",
                "block contact",
                "unblock contact",
            ],
            "contact list" => [
                "view contact list",
                "add contact list",
                "edit contact list",
                "delete contact list",
                "view list contact",
                "add contact to list",
                "remove contact from list",
            ],
            "contact tag" => [
                "view contact tag",
                "add contact tag",
                "edit contact tag",
                "delete contact tag",
            ],
            "whatsapp" => [
                "view inbox",
                "send message",
                "view contact name",
                "view contact mobile",
                "view contact profile",
            ],
            "customer" => [
                "view customer",
                "add customer",
                "edit customer",
                "delete customer",
            ],
            "template" => [
                "view template",
                "edit template",
                "add template",
                "delete template",
            ],
            "cta url" => [
                "view cta url",
                "add cta url",
                "delete cta url",
            ],
            "ai assistant" => [
                "ai assistant settings",
            ],
            "campaign" => [
                "view campaign",
                "add campaign",
                "edit campaign",
                "delete campaign",
            ],
            "welcome message" => [
                "view welcome message",
                "add welcome message",
                "edit welcome message",
            ],
            "flow builder" => [
                "view flow builder",
                "edit flow builder",
                "add flow builder",
                "delete flow builder",
            ],
            "interactive list" => [
                "view interactive list",
                "add interactive list",
                "delete interactive list",
            ],
            "agent" => [
                "view agent",
                "add agent",
                "edit agent",
                "view permission",
                "assign permission",
                "delete agent",
            ],
            "ecommerce" => [
                "update configuration",
                "show products",
                "order products"
            ],
            "external api" => [
                "external api access",
                "ip configuration action"
            ],
            "shortlink" => [
                "view shortlink",
                "add shortlink",
                "edit shortlink",
                "delete shortlink",
            ],
            "floater" => [
                "view floater",
                "add floater",
                "delete floater",
            ],
            "other" => [
                "view dashboard",
                "view wallet",
                "view subscription",
            ]
        ];

        $apiPermissions = ['external api access'];

        foreach ($permissions as $k => $permission) {
            foreach ($permission as  $item) {
                $exists = AgentPermission::where("name", $item)->where('group_name', $k)->exists();
                if ($exists) continue;
                $permission             = new AgentPermission();
                $permission->name       = $item;
                $permission->group_name = $k;
                $permission->guard_name = in_array($item, $apiPermissions) ? 'api' : 'web';
                $permission->save();
            }
        }
    }
}
