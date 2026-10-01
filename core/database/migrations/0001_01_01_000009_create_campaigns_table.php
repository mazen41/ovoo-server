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
        if (Schema::hasTable('campaigns')) {
            return;
        }

        Schema::create('campaigns', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('whatsapp_account_id')->default(0);
            $table->string('title', 255)->nullable();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('template_id')->default(0);
            $table->text('template_header_params')->nullable();
            $table->text('template_body_params')->nullable();
            $table->timestamp('send_at')->nullable();
            $table->boolean('status')->default(0)->comment('0=init,1=completed,2=running,3=scheduled,9=failed');
            $table->integer('total_message')->default(0);
            $table->integer('total_success')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
