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
        if (Schema::hasTable('flow_edges')) {
            return;
        }

        Schema::create('flow_edges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('flow_id')->nullable()->default(0);
            $table->string('source_node_id', 255)->nullable();
            $table->string('target_node_id', 255)->nullable();
            $table->integer('button_index')->nullable();
            $table->json('edge_json')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flow_edges');
    }
};
