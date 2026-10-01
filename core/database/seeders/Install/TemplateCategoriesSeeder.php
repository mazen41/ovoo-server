<?php

namespace Database\Seeders\Install;

/**
 * Reference data for the template_categories table, generated from install/database.sql.
 *
 * Primary keys are written explicitly because other seeded tables reference them by id.
 */
class TemplateCategoriesSeeder extends InstallSeeder
{
    public function run(): void
    {
        $this->insertRows('template_categories', [
            ['id' => '1', 'name' => 'MARKETING', 'label' => 'Marketing'],
            ['id' => '2', 'name' => 'UTILITY', 'label' => 'Utility'],
            ['id' => '3', 'name' => 'AUTHENTICATION', 'label' => 'Authentication'],
        ]);
    }
}
