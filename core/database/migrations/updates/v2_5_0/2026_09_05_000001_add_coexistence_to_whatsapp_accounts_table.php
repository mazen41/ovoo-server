<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Unlike the create migrations, this one ALTERs an existing table, so the guard has to be
        // inverted: bail when the table is missing or the columns are already present.
        if (!Schema::hasTable('whatsapp_accounts') || Schema::hasColumn('whatsapp_accounts', 'is_coexistence')) {
            return;
        }

        Schema::table('whatsapp_accounts', function (Blueprint $table) {
            $table->boolean('is_coexistence')->default(0)->after('phone_number_status')->comment('0=NO,1=YES');
            $table->string('business_id', 255)->nullable()->after('is_coexistence');
            $table->string('platform_type', 40)->nullable()->after('business_id')->comment('CLOUD_API|SMB_APP as reported by Meta');
            $table->boolean('coexistence_automation')->default(1)->after('platform_type')->comment('0=NO,1=YES');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('whatsapp_accounts') || !Schema::hasColumn('whatsapp_accounts', 'is_coexistence')) {
            return;
        }

        Schema::table('whatsapp_accounts', function (Blueprint $table) {
            $table->dropColumn(['is_coexistence', 'business_id', 'platform_type', 'coexistence_automation']);
        });
    }
};
