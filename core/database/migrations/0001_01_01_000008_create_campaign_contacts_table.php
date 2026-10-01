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
        if (Schema::hasTable('campaign_contacts')) {
            return;
        }

        Schema::create('campaign_contacts', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedBigInteger('campaign_id')->default(0);
            $table->unsignedBigInteger('contact_id')->default(0);
            $table->boolean('is_failed')->default(0);
            $table->dateTime('send_at')->nullable();
            $table->tinyInteger('is_trigger')->default(0);
            $table->longText('error_message')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_contacts');
    }
};
