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
        if (Schema::hasTable('admins')) {
            return;
        }

        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name', 40)->nullable();
            $table->string('email', 40)->nullable();
            $table->string('username', 40)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('image', 255)->nullable();
            $table->string('password', 255);
            $table->string('remember_token', 255)->nullable();
            $table->boolean('status')->default(1)->comment('1=active,0=disable');
            $table->timestamps();

            $table->unique(['email', 'username'], 'email');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
