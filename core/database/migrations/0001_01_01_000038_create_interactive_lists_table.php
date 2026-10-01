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
        if (Schema::hasTable('interactive_lists')) {
            return;
        }

        Schema::create('interactive_lists', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('name', 255)->nullable();
            $table->string('button_text', 40)->nullable();
            $table->text('header')->nullable();
            $table->text('body')->nullable();
            $table->text('footer')->nullable();
            $table->text('sections')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interactive_lists');
    }
};
