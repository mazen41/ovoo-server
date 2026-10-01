<?php

/*
|----------------------------------------------------------------------------------------------
| Installer
|----------------------------------------------------------------------------------------------
|
| This is the only file that needs editing when the installer is reused in another project.
| Everything else under app/Http/Controllers/Install, app/Lib/Installer.php and
| resources/views/install is project agnostic.
|
*/

return [

    'product_name' => 'OvoWpp',
    'product_slug' => 'ovowpp',

    // Shown on the wizard's dark sidebar, so this wants the light version of the logo. Relative
    // to the public root; the product name is used instead when the file is missing.
    'logo' => 'assets/images/logo_icon/logo_dark.png',

    // Accent for buttons, the active step and focus rings. The only value to change to rebrand.
    'brand_color' => '#ff6200',

    'verify_purchase' => env('INSTALL_VERIFY_PURCHASE', true),
    'verify_url'      => env('INSTALL_VERIFY_URL', 'https://license.ovosolution.com/product-verify'),

    'lock_file' => 'installed',

    'requirements' => [

        'php' => '8.3',

        'extensions' => [
            'bcmath',
            'ctype',
            'curl',
            'fileinfo',
            'gd',
            'json',
            'mbstring',
            'openssl',
            'pdo',
            'pdo_mysql',
            'tokenizer',
            'xml',
            'zip',
        ],

        'writable' => [
            '.env',
            'storage',
            'storage/app',
            'storage/framework',
            'storage/logs',
            'bootstrap/cache',
        ],
    ],
];
