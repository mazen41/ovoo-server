<?php

use App\Constants\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('general_settings') && !Schema::hasColumn('general_settings', 'whatsapp_coexistence_signup')) {
            Schema::table('general_settings', function (Blueprint $table) {
                $table->boolean('whatsapp_coexistence_signup')->default(0)->after('whatsapp_embedded_signup')->comment('0=disable,1=enable');
                $table->text('meta_coexistence_configuration_id')->nullable()->after('meta_configuration_id');
            });
        }

        // Existing installations never re-run the seeders, so the cron row has to be inserted here.
        if (Schema::hasTable('cron_jobs') && !DB::table('cron_jobs')->where('alias', 'coexistence_media_sync')->exists()) {
            DB::table('cron_jobs')->insert([
                'name'             => 'Coexistence Media Sync',
                'alias'            => 'coexistence_media_sync',
                'action'           => json_encode(['\App\Http\Controllers\CronController', 'coexistenceMediaSync']),
                'url'              => '',
                'cron_schedule_id' => 1, // Hourly
                'next_run'         => now(),
                'last_run'         => now(),
                'is_running'       => Status::YES,
                'is_default'       => Status::YES,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cron_jobs')) {
            DB::table('cron_jobs')->where('alias', 'coexistence_media_sync')->delete();
        }

        if (Schema::hasTable('general_settings') && Schema::hasColumn('general_settings', 'whatsapp_coexistence_signup')) {
            Schema::table('general_settings', function (Blueprint $table) {
                $table->dropColumn(['whatsapp_coexistence_signup', 'meta_coexistence_configuration_id']);
            });
        }
    }
};
