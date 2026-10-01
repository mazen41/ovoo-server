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
        if (Schema::hasTable('messages')) {
            return;
        }

        Schema::create('messages', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->bigInteger('user_id')->default(0);
            $table->text('whatsapp_message_id')->nullable();
            $table->unsignedBigInteger('reply_to_id')->default(0);
            $table->integer('whatsapp_account_id')->default(0);
            $table->unsignedBigInteger('campaign_id')->default(0);
            $table->unsignedBigInteger('chatbot_id')->default(0);
            $table->unsignedBigInteger('template_id')->default(0);
            $table->unsignedBigInteger('conversation_id')->default(0);
            $table->unsignedBigInteger('agent_id')->default(0);
            $table->unsignedBigInteger('cta_url_id')->default(0);
            $table->unsignedBigInteger('interactive_list_id')->default(0);
            $table->longText('message')->nullable();
            $table->boolean('type')->default(1)->comment('1=sent,2=received');
            $table->boolean('message_type')->default(1)->comment('1=text,2=image,3=video,4=document');
            $table->text('media_id')->nullable();
            $table->text('media_url')->nullable();
            $table->string('media_type', 40)->nullable();
            $table->text('mime_type')->nullable();
            $table->longText('media_caption')->nullable();
            $table->string('media_filename', 255)->nullable();
            $table->text('media_path')->nullable();
            $table->text('location')->nullable();
            $table->text('product_data')->nullable();
            $table->text('list_reply')->nullable();
            $table->dateTime('send_at')->nullable();
            $table->boolean('status')->default(1)->comment('1=sent,2=delivered,3=read,9=failed');
            $table->dateTime('ordering')->nullable();
            $table->boolean('ai_reply')->default(0)->comment('1=yes,0=no');
            $table->unsignedBigInteger('flow_id')->default(0);
            $table->unsignedBigInteger('flow_node_id')->default(0);
            $table->longText('error_message')->nullable();
            $table->boolean('channel')->default(1)->comment('Whatsapp=1,telegram=2');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
