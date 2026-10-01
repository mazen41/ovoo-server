<?php

namespace Database\Seeders\Install;

/**
 * Reference data for the pages table, generated from install/database.sql.
 *
 * Primary keys are written explicitly because other seeded tables reference them by id.
 */
class PagesSeeder extends InstallSeeder
{
    public function run(): void
    {
        $this->insertRows('pages', [
            ['id' => '1', 'name' => 'HOME', 'slug' => '/', 'tempname' => 'templates.basic.', 'secs' => '["how_it_work","feature","pricing","testimonial","mobile_app","faq","cta","blog"]', 'seo_content' => '{"image":"670d1fed046621728913389.png","description":"Et recusandae Minus","social_title":"test","social_description":"Odit magna eos cons","keywords":null}', 'is_default' => '1', 'created_at' => '2020-07-11 06:23:58', 'updated_at' => '2025-06-03 13:12:25'],
            ['id' => '4', 'name' => 'Blog', 'slug' => 'blog', 'tempname' => 'templates.basic.', 'secs' => null, 'seo_content' => null, 'is_default' => '1', 'created_at' => '2020-10-22 01:14:43', 'updated_at' => '2025-05-31 15:56:08'],
            ['id' => '5', 'name' => 'Contact', 'slug' => 'contact', 'tempname' => 'templates.basic.', 'secs' => '["faq"]', 'seo_content' => null, 'is_default' => '1', 'created_at' => '2020-10-22 01:14:53', 'updated_at' => '2025-05-31 10:29:33'],
            ['id' => '28', 'name' => 'Features', 'slug' => 'feature', 'tempname' => 'templates.basic.', 'secs' => '["feature","cta"]', 'seo_content' => null, 'is_default' => '1', 'created_at' => '2020-10-22 01:14:53', 'updated_at' => '2025-05-31 15:53:43'],
            ['id' => '29', 'name' => 'Pricing', 'slug' => 'pricing', 'tempname' => 'templates.basic.', 'secs' => '["faq","cta"]', 'seo_content' => null, 'is_default' => '1', 'created_at' => '2020-10-22 01:14:53', 'updated_at' => '2025-02-08 00:37:31'],
        ]);
    }
}
