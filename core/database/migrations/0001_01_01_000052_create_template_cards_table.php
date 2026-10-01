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
        if (Schema::hasTable('template_cards')) {
            return;
        }

        Schema::create('template_cards', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->unsignedBigInteger('template_id')->default(0);
            $table->string('header_format', 40)->nullable()->default('IMAGE')->comment('IMAGE or VIDEO');
            $table->text('header')->nullable();
            $table->string('body', 255)->nullable();
            $table->text('buttons')->nullable();
            $table->string('media_id', 255)->nullable();
            $table->text('media_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('template_cards');
    }
};
