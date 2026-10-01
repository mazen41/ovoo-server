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
        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 40)->nullable();
            $table->string('lastname', 40)->nullable();
            $table->string('username', 40)->nullable();
            $table->string('email', 40);
            $table->string('image', 255)->nullable();
            $table->string('dial_code', 40)->nullable();
            $table->string('mobile', 40)->nullable();
            $table->unsignedInteger('ref_by')->default(0);
            $table->decimal('balance', 28, 8)->default(0);
            $table->string('password', 255);
            $table->string('country_name', 255)->nullable();
            $table->string('country_code', 40)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('state', 255)->nullable();
            $table->string('zip', 255)->nullable();
            $table->text('address')->nullable();
            $table->boolean('status')->default(1)->comment('0: banned, 1: active');
            $table->text('kyc_data')->nullable();
            $table->string('kyc_rejection_reason', 255)->nullable();
            $table->boolean('kv')->default(0)->comment('0: KYC Unverified, 2: KYC pending, 1: KYC verified');
            $table->boolean('ev')->default(0)->comment('0: email unverified, 1: email verified');
            $table->boolean('sv')->default(0)->comment('0: mobile unverified, 1: mobile verified');
            $table->boolean('profile_complete')->default(0);
            $table->string('ver_code', 40)->nullable()->comment('stores verification code');
            $table->dateTime('ver_code_send_at')->nullable()->comment('verification send time');
            $table->boolean('ts')->default(0)->comment('0: 2fa off, 1: 2fa on');
            $table->boolean('tv')->default(1)->comment('0: 2fa unverified, 1: 2fa verified');
            $table->string('tsc', 255)->nullable();
            $table->boolean('en')->default(1);
            $table->boolean('sn')->default(1);
            $table->boolean('pn')->default(1);
            $table->string('ban_reason', 255)->nullable();
            $table->string('remember_token', 255)->nullable();
            $table->string('provider', 40)->nullable();
            $table->string('provider_id', 255)->nullable();
            $table->integer('parent_id')->default(0);
            $table->boolean('is_agent')->default(0);
            $table->integer('plan_id')->default(0);
            $table->integer('account_limit')->default(0);
            $table->integer('contact_limit')->default(0);
            $table->integer('template_limit')->default(0);
            $table->boolean('welcome_message')->default(0);
            $table->boolean('ai_assistance')->default(0)->comment('1=yes,0=no');
            $table->boolean('ecommerce_available')->default(0)->comment('0 = no, 1 = yes');
            $table->boolean('interactive_message')->default(0)->comment('1=available,0=not available');
            $table->integer('chatbot_limit')->default(0);
            $table->integer('campaign_limit')->default(0);
            $table->integer('flow_limit')->default(0);
            $table->integer('short_link_limit')->default(0);
            $table->integer('floater_limit')->default(0);
            $table->integer('agent_limit')->default(0);
            $table->dateTime('plan_expired_at')->nullable();
            $table->boolean('api_available')->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->timestamps();

            $table->unique(['username', 'email'], 'username');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
