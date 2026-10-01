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
        if (Schema::hasTable('template_categories')) {
            return;
        }

        Schema::create('template_categories', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name', 40)->nullable();
            $table->string('label', 40)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_categories');
    }
};
