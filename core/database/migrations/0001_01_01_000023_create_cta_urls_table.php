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
        if (Schema::hasTable('cta_urls')) {
            return;
        }

        Schema::create('cta_urls', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('name', 40)->nullable();
            $table->string('cta_url', 255)->nullable();
            $table->string('header_format', 40)->nullable();
            $table->text('header')->nullable();
            $table->text('body')->nullable();
            $table->text('action')->nullable();
            $table->string('footer', 60)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cta_urls');
    }
};
