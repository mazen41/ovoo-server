<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * The Add/Edit Agent form treats these users columns as optional:
 * lastname, mobile, dial_code, country_code, country_name, city, state, zip, address.
 *
 * Each column is changed to nullable only if it is NOT NULL in the current database. On
 * databases created from install/database.sql they are already nullable, so this migration
 * changes nothing there. Laravel 11 changes columns natively (no doctrine/dbal needed).
 */
return new class extends Migration
{
    /** column => [type, length]; type 'text' for address, otherwise 'string'. */
    private array $columns = [
        'lastname'     => ['string', 40],
        'dial_code'    => ['string', 40],
        'mobile'       => ['string', 40],
        'country_code' => ['string', 40],
        'country_name' => ['string', 255],
        'city'         => ['string', 255],
        'state'        => ['string', 255],
        'zip'          => ['string', 255],
        'address'      => ['text', null],
    ];

    public function up(): void
    {
        $database = DB::getDatabaseName();

        foreach ($this->columns as $name => [$type, $length]) {
            $isNullable = DB::table('information_schema.COLUMNS')
                ->where('TABLE_SCHEMA', $database)
                ->where('TABLE_NAME', 'users')
                ->where('COLUMN_NAME', $name)
                ->value('IS_NULLABLE');

            // Skip columns that are already nullable, and columns that do not exist.
            if ($isNullable !== 'NO') {
                continue;
            }

            Schema::table('users', function (Blueprint $table) use ($name, $type, $length) {
                if ($type === 'text') {
                    $table->text($name)->nullable()->change();
                } else {
                    $table->string($name, $length)->nullable()->change();
                }
            });
        }
    }

    /**
     * Not reversed. Making a column NOT NULL again fails when agents have empty values,
     * so the down step is a no-op.
     */
    public function down(): void
    {
        //
    }
};
