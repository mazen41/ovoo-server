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
        if (Schema::hasTable('notification_templates')) {
            return;
        }

        Schema::create('notification_templates', function (Blueprint $table) {
            $table->id();
            $table->string('act', 40)->nullable();
            $table->string('name', 40)->nullable();
            $table->string('subject', 255)->nullable();
            $table->string('push_title', 255)->nullable();
            $table->text('email_body')->nullable();
            $table->text('sms_body')->nullable();
            $table->text('push_body')->nullable();
            $table->text('shortcodes')->nullable();
            $table->boolean('email_status')->default(1);
            $table->string('email_sent_from_name', 40)->nullable();
            $table->string('email_sent_from_address', 40)->nullable();
            $table->boolean('sms_status')->default(1);
            $table->string('sms_sent_from', 40)->nullable();
            $table->boolean('push_status')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_templates');
    }
};
