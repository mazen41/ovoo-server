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
        if (Schema::hasTable('welcome_messages')) {
            return;
        }

        Schema::create('welcome_messages', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('whatsapp_account_id')->default(0);
            $table->text('message')->nullable();
            $table->boolean('status')->default(0)->comment('
');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('welcome_messages');
    }
};
