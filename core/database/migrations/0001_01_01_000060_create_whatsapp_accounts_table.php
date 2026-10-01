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
        if (Schema::hasTable('whatsapp_accounts')) {
            return;
        }

        Schema::create('whatsapp_accounts', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedBigInteger('user_id');
            $table->string('business_name', 256)->nullable();
            $table->string('whatsapp_business_account_id', 255)->nullable();
            $table->string('phone_number', 40)->nullable();
            $table->string('phone_number_id', 255);
            $table->longText('access_token')->nullable();
            $table->string('code_verification_status', 40)->default('NOT_VERIFIED');
            $table->string('meta_app_id', 255)->nullable();
            $table->boolean('is_default')->default(0)->comment('0=NO,1=YES');
            $table->longText('test_template')->nullable();
            $table->text('phone_number_status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_accounts');
    }
};
