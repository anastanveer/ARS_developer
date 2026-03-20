<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chat_conversations', function (Blueprint $table) {
            $table->string('preferred_channel', 20)->default('chat')->after('phone');
            $table->timestamp('admin_typing_at')->nullable()->after('last_admin_message_at');
        });

        Schema::table('chat_messages', function (Blueprint $table) {
            $table->string('channel', 20)->nullable()->after('body');
            $table->string('external_message_id', 120)->nullable()->after('channel');
            $table->string('message_status', 40)->nullable()->after('external_message_id');
            $table->json('raw_payload')->nullable()->after('message_status');

            $table->index('external_message_id');
        });
    }

    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropIndex(['external_message_id']);
            $table->dropColumn(['channel', 'external_message_id', 'message_status', 'raw_payload']);
        });

        Schema::table('chat_conversations', function (Blueprint $table) {
            $table->dropColumn(['preferred_channel', 'admin_typing_at']);
        });
    }
};
