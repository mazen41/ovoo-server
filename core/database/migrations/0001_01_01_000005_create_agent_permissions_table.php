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
        if (Schema::hasTable('agent_permissions')) {
            return;
        }

        Schema::create('agent_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('guard_name', 255);
            $table->string('group_name', 255);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_permissions');
    }
};
