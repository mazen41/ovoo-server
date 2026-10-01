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
        if (Schema::hasTable('agent_has_permissions')) {
            return;
        }

        Schema::create('agent_has_permissions', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedBigInteger('agent_permission_id')->default(0);
            $table->unsignedBigInteger('agent_id')->default(0);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_has_permissions');
    }
};
