<?php
$file = "/home/ovowpp/htdocs/ovowpp.loop-pr.com/core/routes/api.php";
$content = file_get_contents($file);

// Clean up previous attempts and duplicates
$lines = explode("\n", $content);
$cleaned_lines = [];
foreach ($lines as $line) {
    if (strpos($line, 'cta-url/get-list') !== false || strpos($line, 'interactive-list/get-list') !== false) {
        continue;
    }
    if (trim($line) === '// Cta URL' || trim($line) === '// Interactive List') {
        continue;
    }
    $cleaned_lines[] = $line;
}
$content = implode("\n", $cleaned_lines);

// Insert clean absolute routes (with leading backslash)
$insert = <<<TEXT
                // Cta URL
                Route::get('cta-url/get-list', '\App\Http\Controllers\User\CTAUrlController@getList');

                // Interactive List
                Route::get('interactive-list/get-list', '\App\Http\Controllers\User\InteractiveListController@getList');


TEXT;

$content = str_replace("                // Contact", $insert . "                // Contact", $content);
file_put_contents($file, $content);
echo "API routes cleaned and patched successfully!\n";
