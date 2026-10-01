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
        if (Schema::hasTable('deposits')) {
            return;
        }

        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedBigInteger('plan_id')->default(0);
            $table->unsignedBigInteger('coupon_id')->default(0);
            $table->boolean('plan_recurring_type')->default(0)->comment('1=monthly,2=yearly');
            $table->unsignedInteger('method_code')->default(0);
            $table->decimal('amount', 28, 8)->default(0);
            $table->string('method_currency', 40)->nullable();
            $table->decimal('charge', 28, 8)->default(0);
            $table->decimal('rate', 28, 8)->default(0);
            $table->decimal('final_amount', 28, 8)->default(0);
            $table->text('detail')->nullable();
            $table->string('btc_amount', 255)->nullable();
            $table->string('btc_wallet', 255)->nullable();
            $table->string('trx', 40)->nullable();
            $table->integer('payment_try')->default(0);
            $table->boolean('status')->default(0)->comment('1=>success, 2=>pending, 3=>cancel');
            $table->boolean('from_api')->default(0);
            $table->string('admin_feedback', 255)->nullable();
            $table->string('success_url', 255)->nullable();
            $table->string('failed_url', 255)->nullable();
            $table->integer('last_cron')->nullable()->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
