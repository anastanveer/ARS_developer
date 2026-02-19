<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admin_notification_reads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_user_id')->constrained('admin_users')->cascadeOnDelete();
            $table->string('activity_type', 40);
            $table->unsignedBigInteger('activity_id');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->unique(['admin_user_id', 'activity_type', 'activity_id'], 'admin_notif_unique');
            $table->index(['admin_user_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_notification_reads');
    }
};
