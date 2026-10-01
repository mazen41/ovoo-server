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
        if (Schema::hasTable('flows')) {
            return;
        }

        Schema::create('flows', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name', 40)->nullable();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->integer('whatsapp_account_id')->default(0);
            $table->boolean('trigger_type')->default(1)->comment('1=new message,2=keyword');
            $table->string('keyword', 255)->nullable();
            $table->boolean('status')->default(1)->comment('1=active,0=inactive');
            $table->json('nodes_json')->nullable();
            $table->json('edges_json')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flows');
    }
};
