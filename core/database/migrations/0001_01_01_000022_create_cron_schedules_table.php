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
        if (Schema::hasTable('cron_schedules')) {
            return;
        }

        Schema::create('cron_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40)->nullable();
            $table->unsignedInteger('interval')->default(0);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cron_schedules');
    }
};
