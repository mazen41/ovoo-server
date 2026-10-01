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
        if (Schema::hasTable('ecommerce_configurations')) {
            return;
        }

        Schema::create('ecommerce_configurations', function (Blueprint $table) {
            $table->collation('utf8mb4_general_ci');

            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->text('config')->nullable();
            $table->boolean('provider')->default(1);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ecommerce_configurations');
    }
};
