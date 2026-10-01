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
        if (Schema::hasTable('coupons')) {
            return;
        }

        Schema::create('coupons', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('code', 40)->nullable();
            $table->string('name', 255)->nullable();
            $table->boolean('type')->default(1)->comment('1=fixed,2=percent');
            $table->decimal('amount', 28, 8)->default(0);
            $table->decimal('min_purchase_amount', 28, 8)->default(0);
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('use_limit')->default(0);
            $table->integer('per_user_limit')->default(0);
            $table->boolean('status')->default(1)->comment('1=available,0=disable');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};
