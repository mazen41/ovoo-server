<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('messages') || Schema::hasColumn('messages', 'message_origin')) {
            return;
        }

        Schema::table('messages', function (Blueprint $table) {
            // Defaults to platform, so every pre-existing row keeps behaving exactly as before.
            $table->boolean('message_origin')->default(1)->after('type')->comment('1=platform,2=device,3=history');
            $table->boolean('media_sync_status')->nullable()->after('media_path')->comment('null=n/a,1=pending,2=synced,3=unavailable');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('messages') || !Schema::hasColumn('messages', 'message_origin')) {
            return;
        }

        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['message_origin', 'media_sync_status']);
        });
    }
};
