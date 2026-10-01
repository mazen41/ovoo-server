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
        if (Schema::hasTable('pricing_plans')) {
            return;
        }

        Schema::create('pricing_plans', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->string('name', 40)->nullable();
            $table->string('description', 255)->nullable();
            $table->decimal('monthly_price', 28, 8)->default(0);
            $table->decimal('yearly_price', 28, 8)->default(0);
            $table->integer('account_limit')->default(0)->comment('how many waba can be added');
            $table->integer('contact_limit')->default(0);
            $table->integer('template_limit')->default(0);
            $table->boolean('welcome_message')->default(0);
            $table->boolean('ai_assistance')->default(0)->comment('1=yes,0=no');
            $table->boolean('interactive_message')->default(0)->comment('1=available,0=not available');
            $table->boolean('ecommerce_available')->default(0)->comment('0 = off, 1 = on');
            $table->integer('chatbot_limit')->default(0);
            $table->integer('campaign_limit')->default(0);
            $table->integer('flow_limit')->default(0);
            $table->integer('short_link_limit')->default(0);
            $table->integer('floater_limit')->default(0);
            $table->integer('agent_limit')->default(0);
            $table->boolean('status')->nullable()->default(1);
            $table->boolean('is_popular')->default(0);
            $table->boolean('api_available')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pricing_plans');
    }
};
