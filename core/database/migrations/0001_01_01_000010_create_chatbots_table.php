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
        if (Schema::hasTable('chatbots')) {
            return;
        }

        Schema::create('chatbots', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('title', 255)->nullable();
            $table->unsignedBigInteger('whatsapp_account_id')->default(0);
            $table->bigInteger('user_id')->default(0);
            $table->text('keywords')->nullable();
            $table->text('text')->nullable();
            $table->boolean('status')->default(1)->comment('1=active,0=inactive');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chatbots');
    }
};
