<?php

namespace Database\Seeders\Install;

/**
 * Reference data for the agent_permissions table, generated from install/database.sql.
 *
 * Primary keys are written explicitly because other seeded tables reference them by id.
 */
class AgentPermissionsSeeder extends InstallSeeder
{
    public function run(): void
    {
        $this->insertRows('agent_permissions', [
            ['id' => '1', 'name' => 'view contact', 'guard_name' => 'web', 'group_name' => 'contact', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '2', 'name' => 'add contact', 'guard_name' => 'web', 'group_name' => 'contact', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '3', 'name' => 'edit contact', 'guard_name' => 'web', 'group_name' => 'contact', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '4', 'name' => 'delete contact', 'guard_name' => 'web', 'group_name' => 'contact', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '5', 'name' => 'view contact list', 'guard_name' => 'web', 'group_name' => 'contact list', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '6', 'name' => 'add contact list', 'guard_name' => 'web', 'group_name' => 'contact list', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '7', 'name' => 'edit contact list', 'guard_name' => 'web', 'group_name' => 'contact list', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '8', 'name' => 'delete contact list', 'guard_name' => 'web', 'group_name' => 'contact list', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '9', 'name' => 'view list contact', 'guard_name' => 'web', 'group_name' => 'contact list', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '10', 'name' => 'add contact to list', 'guard_name' => 'web', 'group_name' => 'contact list', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '11', 'name' => 'remove contact from list', 'guard_name' => 'web', 'group_name' => 'contact list', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '12', 'name' => 'view contact tag', 'guard_name' => 'web', 'group_name' => 'contact tag', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '13', 'name' => 'add contact tag', 'guard_name' => 'web', 'group_name' => 'contact tag', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '14', 'name' => 'edit contact tag', 'guard_name' => 'web', 'group_name' => 'contact tag', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '15', 'name' => 'delete contact tag', 'guard_name' => 'web', 'group_name' => 'contact tag', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '16', 'name' => 'view inbox', 'guard_name' => 'web', 'group_name' => 'whatsapp', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '17', 'name' => 'send message', 'guard_name' => 'web', 'group_name' => 'whatsapp', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '18', 'name' => 'view customer', 'guard_name' => 'web', 'group_name' => 'customer', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '19', 'name' => 'add customer', 'guard_name' => 'web', 'group_name' => 'customer', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '20', 'name' => 'edit customer', 'guard_name' => 'web', 'group_name' => 'customer', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '21', 'name' => 'delete customer', 'guard_name' => 'web', 'group_name' => 'customer', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '22', 'name' => 'view template', 'guard_name' => 'web', 'group_name' => 'template', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '23', 'name' => 'edit template', 'guard_name' => 'web', 'group_name' => 'template', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '24', 'name' => 'add template', 'guard_name' => 'web', 'group_name' => 'template', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '25', 'name' => 'delete template', 'guard_name' => 'web', 'group_name' => 'template', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '26', 'name' => 'view campaign', 'guard_name' => 'web', 'group_name' => 'campaign', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '27', 'name' => 'add campaign', 'guard_name' => 'web', 'group_name' => 'campaign', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '28', 'name' => 'edit campaign', 'guard_name' => 'web', 'group_name' => 'campaign', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '29', 'name' => 'delete campaign', 'guard_name' => 'web', 'group_name' => 'campaign', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '34', 'name' => 'view welcome message', 'guard_name' => 'web', 'group_name' => 'welcome message', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '35', 'name' => 'add welcome message', 'guard_name' => 'web', 'group_name' => 'welcome message', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '36', 'name' => 'edit welcome message', 'guard_name' => 'web', 'group_name' => 'welcome message', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '37', 'name' => 'view agent', 'guard_name' => 'web', 'group_name' => 'agent', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '38', 'name' => 'add agent', 'guard_name' => 'web', 'group_name' => 'agent', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '39', 'name' => 'edit agent', 'guard_name' => 'web', 'group_name' => 'agent', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '40', 'name' => 'view permission', 'guard_name' => 'web', 'group_name' => 'agent', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '41', 'name' => 'assign permission', 'guard_name' => 'web', 'group_name' => 'agent', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '42', 'name' => 'delete agent', 'guard_name' => 'web', 'group_name' => 'agent', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '43', 'name' => 'view shortlink', 'guard_name' => 'web', 'group_name' => 'shortlink', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '44', 'name' => 'add shortlink', 'guard_name' => 'web', 'group_name' => 'shortlink', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '45', 'name' => 'edit shortlink', 'guard_name' => 'web', 'group_name' => 'shortlink', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '46', 'name' => 'delete shortlink', 'guard_name' => 'web', 'group_name' => 'shortlink', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '47', 'name' => 'view floater', 'guard_name' => 'web', 'group_name' => 'floater', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '48', 'name' => 'add floater', 'guard_name' => 'web', 'group_name' => 'floater', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '49', 'name' => 'delete floater', 'guard_name' => 'web', 'group_name' => 'floater', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '50', 'name' => 'view dashboard', 'guard_name' => 'web', 'group_name' => 'other', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '51', 'name' => 'view wallet', 'guard_name' => 'web', 'group_name' => 'other', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '52', 'name' => 'view subscription', 'guard_name' => 'web', 'group_name' => 'other', 'created_at' => '2025-06-17 10:44:39', 'updated_at' => '2025-06-17 10:44:39'],
            ['id' => '53', 'name' => 'view contact name', 'guard_name' => 'web', 'group_name' => 'whatsapp', 'created_at' => null, 'updated_at' => null],
            ['id' => '54', 'name' => 'view contact mobile', 'guard_name' => 'web', 'group_name' => 'whatsapp', 'created_at' => null, 'updated_at' => null],
            ['id' => '55', 'name' => 'view contact profile', 'guard_name' => 'web', 'group_name' => 'whatsapp', 'created_at' => null, 'updated_at' => null],
            ['id' => '56', 'name' => 'view cta url', 'guard_name' => 'web', 'group_name' => 'cta url', 'created_at' => null, 'updated_at' => null],
            ['id' => '57', 'name' => 'add cta url', 'guard_name' => 'web', 'group_name' => 'cta url', 'created_at' => null, 'updated_at' => null],
            ['id' => '58', 'name' => 'delete cta url', 'guard_name' => 'web', 'group_name' => 'cta url', 'created_at' => null, 'updated_at' => null],
            ['id' => '59', 'name' => 'ai assistant settings', 'guard_name' => 'web', 'group_name' => 'ai assistant', 'created_at' => null, 'updated_at' => null],
            ['id' => '60', 'name' => 'block contact', 'guard_name' => 'web', 'group_name' => 'whatsapp', 'created_at' => '2025-10-20 16:48:50', 'updated_at' => '2025-10-20 16:48:50'],
            ['id' => '61', 'name' => 'unblock contact', 'guard_name' => 'web', 'group_name' => 'whatsapp', 'created_at' => '2025-10-20 16:48:50', 'updated_at' => '2025-10-20 16:48:50'],
            ['id' => '62', 'name' => 'view flow builder', 'guard_name' => 'web', 'group_name' => 'flow builder', 'created_at' => null, 'updated_at' => null],
            ['id' => '63', 'name' => 'edit flow builder', 'guard_name' => 'web', 'group_name' => 'flow builder', 'created_at' => null, 'updated_at' => null],
            ['id' => '64', 'name' => 'add flow builder', 'guard_name' => 'web', 'group_name' => 'flow builder', 'created_at' => null, 'updated_at' => null],
            ['id' => '65', 'name' => 'delete flow builder', 'guard_name' => 'web', 'group_name' => 'flow builder', 'created_at' => null, 'updated_at' => null],
            ['id' => '66', 'name' => 'view interactive list', 'guard_name' => 'web', 'group_name' => 'interactive list', 'created_at' => null, 'updated_at' => null],
            ['id' => '67', 'name' => 'add interactive list', 'guard_name' => 'web', 'group_name' => 'interactive list', 'created_at' => null, 'updated_at' => null],
            ['id' => '68', 'name' => 'delete interactive list', 'guard_name' => 'web', 'group_name' => 'interactive list', 'created_at' => null, 'updated_at' => null],
            ['id' => '69', 'name' => 'show ecommerce products', 'guard_name' => 'web', 'group_name' => 'ecommerce', 'created_at' => '2025-12-21 09:50:41', 'updated_at' => '2025-12-21 09:50:41'],
            ['id' => '70', 'name' => 'update ecommerce configuration', 'guard_name' => 'web', 'group_name' => 'ecommerce', 'created_at' => '2025-12-21 09:50:41', 'updated_at' => '2025-12-21 09:50:41'],
        ]);
    }
}
