<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_conversation_id')->constrained('chat_conversations')->cascadeOnDelete();
            $table->string('sender_type', 20);
            $table->string('sender_name', 120)->nullable();
            $table->text('body');
            $table->boolean('is_read_by_admin')->default(false);
            $table->boolean('is_read_by_visitor')->default(false);
            $table->timestamps();

            $table->index(['chat_conversation_id', 'created_at']);
            $table->index(['sender_type', 'is_read_by_admin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
