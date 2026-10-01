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
        if (Schema::hasTable('general_settings')) {
            return;
        }

        Schema::create('general_settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name', 40)->nullable();
            $table->string('cur_text', 40)->nullable()->comment('currency text');
            $table->string('cur_sym', 40)->nullable()->comment('currency symbol');
            $table->string('email_from', 40)->nullable();
            $table->string('email_from_name', 255)->nullable();
            $table->text('email_template')->nullable();
            $table->string('sms_template', 255)->nullable();
            $table->decimal('referral_amount_percentage', 5, 2)->default(0)->comment('% of pricing plan price');
            $table->integer('subscription_notify_before')->default(7)->comment('How many days before the notification will send to user for subscription');
            $table->string('webhook_verify_token', 255)->nullable();
            $table->boolean('whatsapp_embedded_signup')->default(0)->comment('0=disable,1=enable');
            $table->string('meta_app_id', 255)->nullable();
            $table->text('meta_app_secret')->nullable();
            $table->text('meta_configuration_id')->nullable();
            $table->text('google_maps_api')->nullable();
            $table->string('sms_from', 255)->nullable();
            $table->string('push_title', 255)->nullable();
            $table->string('push_template', 255)->nullable();
            $table->string('base_color', 40)->nullable();
            $table->text('mail_config')->nullable()->comment('email configuration');
            $table->text('sms_config')->nullable();
            $table->text('firebase_config')->nullable();
            $table->text('global_shortcodes')->nullable();
            $table->boolean('kv')->default(0);
            $table->boolean('ev')->default(0)->comment('email verification, 0 - dont check, 1 - check');
            $table->boolean('en')->default(0)->comment('email notification, 0 - dont send, 1 - send');
            $table->boolean('sv')->default(0)->comment('mobile verication, 0 - dont check, 1 - check');
            $table->boolean('sn')->default(0)->comment('sms notification, 0 - dont send, 1 - send');
            $table->boolean('pn')->default(1);
            $table->boolean('force_ssl')->default(0);
            $table->boolean('in_app_payment')->default(1);
            $table->boolean('maintenance_mode')->default(0);
            $table->boolean('secure_password')->default(0);
            $table->boolean('agree')->default(0);
            $table->boolean('multi_language')->default(1);
            $table->boolean('registration')->default(0)->comment('0: Off	, 1: On');
            $table->string('active_template', 40)->nullable();
            $table->text('socialite_credentials')->nullable();
            $table->dateTime('last_cron')->nullable();
            $table->string('available_version', 40)->nullable();
            $table->boolean('system_customized')->default(0);
            $table->integer('paginate_number')->default(0);
            $table->boolean('currency_format')->default(0)->comment('1=>Both
2=>Text Only
3=>Symbol Only');
            $table->text('time_format')->nullable();
            $table->text('date_format')->nullable();
            $table->integer('allow_precision')->default(2);
            $table->string('thousand_separator', 40)->nullable();
            $table->string('preloader_image', 255)->nullable();
            $table->text('pusher_config')->nullable();
            $table->text('s3_config')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('general_settings');
    }
};
