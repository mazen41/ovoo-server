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
        if (Schema::hasTable('gateways')) {
            return;
        }

        Schema::create('gateways', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('form_id')->default(0);
            $table->integer('code')->nullable();
            $table->string('name', 40)->nullable();
            $table->string('alias', 40)->default('NULL');
            $table->string('image', 255)->nullable();
            $table->boolean('status')->default(1)->comment('1=>enable, 2=>disable');
            $table->text('gateway_parameters')->nullable();
            $table->text('supported_currencies')->nullable();
            $table->boolean('crypto')->default(0)->comment('0: fiat currency, 1: crypto currency');
            $table->text('extra')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['code'], 'code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gateways');
    }
};
