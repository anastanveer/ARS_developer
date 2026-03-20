<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_conversations', function (Blueprint $table) {
            $table->id();
            $table->uuid('public_token')->unique();
            $table->string('name', 120)->nullable();
            $table->string('email', 180)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('status', 30)->default('open');
            $table->string('source_page', 255)->nullable();
            $table->string('visitor_ip', 64)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('last_visitor_message_at')->nullable();
            $table->timestamp('last_admin_message_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'updated_at']);
            $table->index('last_visitor_message_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_conversations');
    }
};
