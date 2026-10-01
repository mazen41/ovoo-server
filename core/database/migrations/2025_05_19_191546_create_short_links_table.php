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
        if (Schema::hasTable('short_links')) {
            return;
        }

        Schema::create('short_links', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('code', 255)->nullable();
            $table->string('dial_code', 10)->nullable();
            $table->string('mobile', 40)->nullable();
            $table->string('message', 255)->nullable();
            $table->string('qr_code', 255)->nullable();
            $table->integer('click')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('short_links');
    }
};
