<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_action_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('audit_report_id')->nullable();
            $table->unsignedBigInteger('audit_scan_run_id')->nullable();
            $table->string('title', 220);
            $table->text('details')->nullable();
            $table->string('severity', 20)->default('medium');
            $table->string('status', 20)->default('open');
            $table->string('owner_name', 120)->nullable();
            $table->date('due_date')->nullable();
            $table->unsignedBigInteger('created_by_admin_user_id')->nullable();
            $table->timestamps();

            $table->index(['status', 'severity']);
            $table->index('audit_report_id');
            $table->index('audit_scan_run_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_action_items');
    }
};
