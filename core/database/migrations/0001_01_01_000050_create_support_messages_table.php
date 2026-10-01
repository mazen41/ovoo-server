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
        if (Schema::hasTable('support_messages')) {
            return;
        }

        Schema::create('support_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('support_ticket_id')->default(0);
            $table->unsignedInteger('admin_id')->default(0);
            $table->longText('message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_messages');
    }
};
