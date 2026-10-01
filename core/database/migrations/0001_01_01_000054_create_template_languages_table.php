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
        if (Schema::hasTable('template_languages')) {
            return;
        }

        Schema::create('template_languages', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('code', 40)->nullable();
            $table->string('country', 40)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_languages');
    }
};
