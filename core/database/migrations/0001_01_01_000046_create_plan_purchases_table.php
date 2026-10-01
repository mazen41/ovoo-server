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
        if (Schema::hasTable('plan_purchases')) {
            return;
        }

        Schema::create('plan_purchases', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedBigInteger('plan_id')->default(0);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('coupon_id')->default(0);
            $table->boolean('recurring_type')->default(1)->comment('1=monthly,2=yearly');
            $table->decimal('amount', 28, 8)->default(0);
            $table->decimal('discount_amount', 28, 8)->default(0);
            $table->boolean('payment_method')->default(1)->comment('1=wallet,2=gateway');
            $table->integer('gateway_method_code')->default(0);
            $table->unsignedTinyInteger('auto_renewal')->default(0);
            $table->dateTime('expired_at')->nullable();
            $table->boolean('is_sent_expired_notify')->default(0);
            $table->boolean('is_sent_reminder_notify')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_purchases');
    }
};
