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
        if (Schema::hasTable('installed_addons')) {
            return;
        }

        Schema::create('installed_addons', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('author', 255)->nullable();
            $table->text('title')->nullable();
            $table->string('slug', 255);
            $table->string('version', 255)->nullable();
            $table->text('provider')->nullable();
            $table->text('description')->nullable();
            $table->string('purchase_code', 255);
            $table->string('envato_username', 255)->nullable();
            $table->string('update_available', 30)->nullable()->comment('Target addon version');
            $table->boolean('status')->default(1)->comment('1=installed,0=uninstall');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installed_addons');
    }
};
