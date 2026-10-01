<?php

namespace Database\Seeders\Install;

/**
 * Reference data for the languages table, generated from install/database.sql.
 *
 * Primary keys are written explicitly because other seeded tables reference them by id.
 */
class LanguagesSeeder extends InstallSeeder
{
    public function run(): void
    {
        $this->insertRows('languages', [
            ['id' => '1', 'name' => 'English', 'code' => 'en', 'is_default' => '1', 'image' => '66dd7636311b31725789750.png', 'info' => 'English is a global language with rich vocabulary, bridging international communication and culture.', 'created_at' => '2020-07-06 03:47:55', 'updated_at' => '2024-10-03 04:11:19'],
            ['id' => '12', 'name' => 'bangla', 'code' => 'bn', 'is_default' => '0', 'image' => '66dd762f478701725789743.png', 'info' => 'Bangla is a rich, expressive language spoken by millions, known for its cultural depth and literary heritage.', 'created_at' => '2024-09-08 01:34:54', 'updated_at' => '2024-10-02 08:10:11'],
            ['id' => '13', 'name' => 'Turkish', 'code' => 'tr', 'is_default' => '0', 'image' => '66dd763ce41bd1725789756.png', 'info' => 'Turkish is a vibrant language with deep historical roots, known for its unique structure and cultural significance.', 'created_at' => '2024-09-08 01:35:12', 'updated_at' => '2024-09-10 05:19:32'],
            ['id' => '14', 'name' => 'Spanish', 'code' => 'es', 'is_default' => '0', 'image' => '66dd764462e2f1725789764.png', 'info' => 'Spanish is a widely spoken language, celebrated for its melodic flow and rich cultural heritage.', 'created_at' => '2024-09-08 01:35:22', 'updated_at' => '2024-10-03 04:11:19'],
            ['id' => '15', 'name' => 'French', 'code' => 'fr', 'is_default' => '0', 'image' => '66dd7652c06061725789778.png', 'info' => 'French is a romantic language, renowned for its elegance, rich literature, and global influence.', 'created_at' => '2024-09-08 01:35:28', 'updated_at' => '2024-10-02 08:10:07'],
            ['id' => '17', 'name' => 'Russian', 'code' => 'ru', 'is_default' => '0', 'image' => '66dd7a31f25a01725790769.png', 'info' => 'Russian is a powerful language, known for its complex grammar and rich literary tradition.', 'created_at' => '2024-09-08 04:19:30', 'updated_at' => '2024-09-10 05:20:29'],
            ['id' => '19', 'name' => 'Portuguese', 'code' => 'pt', 'is_default' => '0', 'image' => '66e6c31120d4c1726399249.png', 'info' => 'Portuguese is a dynamic language with a rich cultural history, known for its expressiveness and global influence.', 'created_at' => '2024-09-15 05:20:49', 'updated_at' => '2024-09-15 05:25:42'],
            ['id' => '23', 'name' => 'Italy', 'code' => 'it', 'is_default' => '0', 'image' => '670781623fe0d1728545122.png', 'info' => 'Italian is a romantic and melodic language, celebrated for its rich history, artistic influence, and cultural significance in music.', 'created_at' => '2024-10-10 01:25:22', 'updated_at' => '2024-10-10 01:27:28'],
            ['id' => '24', 'name' => 'Japanese', 'code' => 'jr', 'is_default' => '0', 'image' => '670cd7835eb281728894851.png', 'info' => 'Japanese is a unique and nuanced language, known for its complex writing and deep cultural significance.', 'created_at' => '2024-10-14 02:34:12', 'updated_at' => '2024-10-14 02:34:12'],
            ['id' => '25', 'name' => 'Arabic', 'code' => 'ar', 'is_default' => '0', 'image' => '692af23d8a2291764422205.png', 'info' => null, 'created_at' => '2025-11-29 13:16:45', 'updated_at' => '2025-11-29 13:16:45'],
        ]);
    }
}
