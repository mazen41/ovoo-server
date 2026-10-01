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
        if (Schema::hasTable('contact_list_contacts')) {
            return;
        }

        Schema::create('contact_list_contacts', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedBigInteger('contact_id')->default(0);
            $table->unsignedBigInteger('contact_list_id')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_list_contacts');
    }
};
