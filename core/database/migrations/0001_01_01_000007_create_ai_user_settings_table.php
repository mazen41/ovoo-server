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
        if (Schema::hasTable('ai_user_settings')) {
            return;
        }

        Schema::create('ai_user_settings', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->longText('system_prompt')->nullable();
            $table->longText('fallback_response')->nullable();
            $table->integer('max_length')->default(512)->comment('Max length of reply');
            $table->boolean('status')->default(0)->comment('1=on,0=off');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_user_settings');
    }
};
