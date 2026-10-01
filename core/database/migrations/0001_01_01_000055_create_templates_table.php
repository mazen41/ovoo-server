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
        if (Schema::hasTable('templates')) {
            return;
        }

        Schema::create('templates', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('whatsapp_template_id', 255)->nullable();
            $table->bigInteger('whatsapp_account_id')->default(0);
            $table->string('name', 512)->nullable()->comment('Meta API allow maximum 512 char');
            $table->text('header')->nullable();
            $table->string('header_format', 255)->nullable();
            $table->longText('header_media')->nullable();
            $table->text('body')->nullable();
            $table->text('buttons')->nullable();
            $table->string('footer', 255)->nullable();
            $table->boolean('add_security_recommendation')->default(0);
            $table->integer('code_expiration_minutes')->nullable();
            $table->unsignedBigInteger('category_id')->default(0);
            $table->unsignedBigInteger('language_id')->default(0);
            $table->boolean('status')->default(0)->comment('0=pending,1=approved,2=rejected,3=disabled
');
            $table->string('rejected_reason', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('templates');
    }
};
