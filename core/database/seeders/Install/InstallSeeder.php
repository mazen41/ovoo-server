<?php

namespace Database\Seeders\Install;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Shared behaviour for the generated installation seeders.
 */
abstract class InstallSeeder extends Seeder
{
    /**
     * Insert reference data.
     *
     * insertOrIgnore keeps the seeder safe to re-run and stops it from overwriting rows an
     * installation has already customised. Rows are chunked so the larger payloads stay well
     * inside max_allowed_packet.
     *
     * The timestamps in this data are UTC, matching install/database.sql which imports under
     * SET time_zone = "+00:00". MySQL converts values written to `timestamp` columns from the
     * session offset into UTC, so without pinning that offset the stored values would shift by
     * whatever timezone the database server happens to run in.
     */
    protected function insertRows(string $table, array $rows): void
    {
        $previous = DB::selectOne('SELECT @@session.time_zone AS tz')->tz;

        DB::statement("SET time_zone = '+00:00'");

        try {
            foreach (array_chunk($rows, 100) as $chunk) {
                DB::table($table)->insertOrIgnore($chunk);
            }
        } finally {
            DB::statement("SET time_zone = '" . $previous . "'");
        }
    }
}
