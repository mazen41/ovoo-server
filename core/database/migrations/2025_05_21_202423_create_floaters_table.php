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
        if (Schema::hasTable('floaters')) {
            return;
        }

        Schema::create('floaters', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('dial_code', 40)->nullable();
            $table->string('mobile', 40)->nullable();
            $table->text('message')->nullable();
            $table->string('color_code', 40);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('floaters');
    }
};
