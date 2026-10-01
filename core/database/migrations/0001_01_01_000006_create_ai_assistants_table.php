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
        if (Schema::hasTable('ai_assistants')) {
            return;
        }

        Schema::create('ai_assistants', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name', 255)->nullable();
            $table->string('info', 255)->nullable();
            $table->string('provider', 255)->nullable();
            $table->text('config')->nullable();
            $table->string('url', 255)->nullable();
            $table->boolean('status')->default(0)->comment('1=enable,0=disable');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_assistants');
    }
};
