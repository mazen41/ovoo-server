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
        if (Schema::hasTable('contact_flow_states')) {
            return;
        }

        Schema::create('contact_flow_states', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('conversation_id')->default(0);
            $table->unsignedBigInteger('flow_id')->default(0);
            $table->string('current_node_id', 255)->nullable()->comment('UUID or ID of the node where the conversation currently is');
            $table->string('last_button_key', 255)->nullable()->comment('Identifier or index of last clicked button');
            $table->boolean('status')->default(0)->comment('0=waiting,1=sent');
            $table->timestamp('last_interacted_at')->nullable()->comment('Last time contact interacted with this flow');
            $table->integer('button_index')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_flow_states');
    }
};
