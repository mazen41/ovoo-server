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
        if (Schema::hasTable('admin_password_resets')) {
            return;
        }

        Schema::create('admin_password_resets', function (Blueprint $table) {
            $table->id();
            $table->string('email', 40)->nullable();
            $table->string('token', 40)->nullable();
            $table->boolean('status')->default(1);
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_password_resets');
    }
};
