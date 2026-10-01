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
        if (Schema::hasTable('user_api_credentials')) {
            return;
        }

        Schema::create('user_api_credentials', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->default(0);
            $table->string('client_id', 255)->nullable();
            $table->string('client_secret', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_api_credentials');
    }
};
