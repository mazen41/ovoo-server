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
        if (Schema::hasTable('gateway_currencies')) {
            return;
        }

        Schema::create('gateway_currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40)->nullable();
            $table->string('currency', 40)->nullable();
            $table->string('symbol', 40)->nullable();
            $table->integer('method_code')->nullable();
            $table->string('gateway_alias', 40)->nullable();
            $table->decimal('min_amount', 28, 8)->default(0);
            $table->decimal('max_amount', 28, 8)->default(0);
            $table->decimal('percent_charge', 5, 2)->default(0);
            $table->decimal('fixed_charge', 28, 8)->default(0);
            $table->decimal('rate', 28, 8)->default(0);
            $table->text('gateway_parameter')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gateway_currencies');
    }
};
