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
        if (Schema::hasTable('conversations')) {
            return;
        }

        Schema::create('conversations', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('contact_id')->default(0);
            $table->boolean('status')->default(0)->comment('1=pending,2=important,3=done');
            $table->integer('whatsapp_account_id')->default(0);
            $table->timestamp('last_message_at')->nullable()->comment('For ordering chatlist');
            $table->boolean('ai_reply')->default(0)->comment('1=yes,0=no');
            $table->integer('agent_id')->default(0);
            $table->boolean('conversation_channel')->default(1)->comment('1=whatsapp,2=telegram');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
