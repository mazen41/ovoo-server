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
        if (Schema::hasTable('contacts')) {
            return;
        }

        Schema::create('contacts', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('firstname', 40)->nullable();
            $table->string('lastname', 40)->nullable();
            $table->string('mobile_code', 40)->nullable();
            $table->string('mobile', 40)->nullable();
            $table->text('image')->nullable();
            $table->text('address')->nullable();
            $table->boolean('status')->default(0);
            $table->text('details')->nullable();
            $table->boolean('is_customer')->default(0);
            $table->boolean('is_marketing_opted_out')->default(0);
            $table->boolean('is_blocked')->default(0)->comment('1=yes,0=no');
            $table->bigInteger('blocked_by')->default(0)->comment('who blocked the contact');
            $table->boolean('contact_channel')->default(1)->comment('1=whatsapp,2=telegram');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
