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
        if (Schema::hasTable('withdraw_methods')) {
            return;
        }

        Schema::create('withdraw_methods', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('form_id')->default(0);
            $table->string('name', 40)->nullable();
            $table->string('image', 255)->nullable();
            $table->decimal('min_limit', 28, 8)->nullable()->default(0);
            $table->decimal('max_limit', 28, 8)->default(0);
            $table->decimal('fixed_charge', 28, 8)->nullable()->default(0);
            $table->decimal('rate', 28, 8)->nullable()->default(0);
            $table->decimal('percent_charge', 5, 2)->nullable();
            $table->string('currency', 40)->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdraw_methods');
    }
};
