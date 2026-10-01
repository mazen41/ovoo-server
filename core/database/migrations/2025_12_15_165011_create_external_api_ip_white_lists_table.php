<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Installations created from install/database.sql already carry this table, so the
        // migration records itself as applied without trying to create it a second time.
        if (Schema::hasTable('external_api_ip_white_lists')) {
            return;
        }

        Schema::create('external_api_ip_white_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('ip', 240);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('external_api_ip_white_lists');
    }
};
