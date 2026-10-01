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
        if (Schema::hasTable('languages')) {
            return;
        }

        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40)->nullable();
            $table->string('code', 40)->nullable();
            $table->boolean('is_default')->default(0)->comment('0: not default language, 1: default language');
            $table->string('image', 40)->nullable();
            $table->text('info')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
