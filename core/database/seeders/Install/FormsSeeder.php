<?php

namespace Database\Seeders\Install;

/**
 * Reference data for the forms table, generated from install/database.sql.
 *
 * Primary keys are written explicitly because other seeded tables reference them by id.
 */
class FormsSeeder extends InstallSeeder
{
    public function run(): void
    {
        $this->insertRows('forms', [
            ['id' => '7', 'act' => 'kyc', 'form_data' => '{"father\'s_name":{"name":"Father\'s Name","label":"father\'s_name","is_required":"required","instruction":null,"extensions":null,"options":[],"type":"text","width":"12"},"mother\'s_name":{"name":"Mother\'s name","label":"mother\'s_name","is_required":"required","instruction":null,"extensions":null,"options":[],"type":"text","width":"12"},"gender":{"name":"Gender","label":"gender","is_required":"required","instruction":null,"extensions":"","options":["Male","Female"],"type":"radio","width":"12"},"nationality":{"name":"Nationality","label":"nationality","is_required":"required","instruction":null,"extensions":null,"options":[],"type":"text","width":"12"},"nid_photo_both_side":{"name":"NID Photo Both Side","label":"nid_photo_both_side","is_required":"required","instruction":null,"extensions":"jpg,jpeg,png","options":[],"type":"file","width":"12"}}', 'created_at' => '2022-03-17 02:56:14', 'updated_at' => '2025-04-09 09:24:53'],
        ]);
    }
}
