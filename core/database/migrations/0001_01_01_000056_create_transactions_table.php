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
        if (Schema::hasTable('transactions')) {
            return;
        }

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->default(0);
            $table->decimal('amount', 28, 8)->default(0);
            $table->decimal('charge', 28, 8)->default(0);
            $table->decimal('post_balance', 28, 8)->default(0);
            $table->string('trx_type', 40)->nullable();
            $table->string('trx', 40)->nullable();
            $table->string('details', 255)->nullable();
            $table->string('remark', 40)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
