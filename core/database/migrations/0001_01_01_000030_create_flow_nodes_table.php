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
        if (Schema::hasTable('flow_nodes')) {
            return;
        }

        Schema::create('flow_nodes', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedBigInteger('flow_id')->default(0);
            $table->string('node_id', 255)->nullable();
            $table->unsignedBigInteger('template_id')->default(1);
            $table->unsignedBigInteger('cta_url_id')->default(0);
            $table->unsignedBigInteger('interactive_list_id')->default(0);
            $table->unsignedBigInteger('flow_node_media_id')->default(0);
            $table->tinyInteger('type')->default(0);
            $table->string('position_x', 255)->nullable();
            $table->string('position_y', 255)->nullable();
            $table->text('text')->nullable();
            $table->text('location')->nullable();
            $table->string('source_node_id', 255)->nullable();
            $table->string('target_node_id', 255)->nullable();
            $table->json('nodes_json')->nullable();
            $table->text('header_params')->nullable();
            $table->text('body_params')->nullable();
            $table->json('buttons_json')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flow_nodes');
    }
};
