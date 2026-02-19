<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30)->default('contact');
            $table->string('name', 120)->nullable();
            $table->string('email', 180);
            $table->string('phone', 50)->nullable();
            $table->string('company', 120)->nullable();
            $table->string('subject', 180)->nullable();
            $table->longText('message')->nullable();
            $table->date('meeting_date')->nullable();
            $table->string('meeting_slot', 120)->nullable();
            $table->string('project_type', 120)->nullable();
            $table->string('budget_range', 120)->nullable();
            $table->string('status', 30)->default('new');
            $table->timestamp('last_followup_at')->nullable();
            $table->string('ip', 64)->nullable();
            $table->string('submitted_from', 255)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['type', 'created_at']);
            $table->index(['meeting_date', 'meeting_slot']);
            $table->index(['email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
