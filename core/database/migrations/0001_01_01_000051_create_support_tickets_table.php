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
        if (Schema::hasTable('support_tickets')) {
            return;
        }

        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable()->default(0);
            $table->string('name', 40)->nullable();
            $table->string('email', 40)->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('ticket', 40)->nullable();
            $table->string('subject', 255)->nullable();
            $table->boolean('status')->default(0)->comment('0: Open, 1: Answered, 2: Replied, 3: Closed');
            $table->boolean('priority')->default(0)->comment('1 = Low, 2 = medium, 3 = heigh');
            $table->dateTime('last_reply')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
