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
        if (Schema::hasTable('frontends')) {
            return;
        }

        Schema::create('frontends', function (Blueprint $table) {
            $table->id();
            $table->string('data_keys', 40)->nullable();
            $table->longText('data_values')->nullable();
            $table->longText('seo_content')->nullable();
            $table->string('tempname', 40)->nullable();
            $table->string('slug', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('frontends');
    }
};
