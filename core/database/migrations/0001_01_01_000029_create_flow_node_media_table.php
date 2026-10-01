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
        if (Schema::hasTable('flow_node_media')) {
            return;
        }

        Schema::create('flow_node_media', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('flow_node_id', 255)->nullable();
            $table->boolean('media_type')->default(1)->comment('1=image,2=video,3=audio,4=document');
            $table->string('media_path', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flow_node_media');
    }
};
