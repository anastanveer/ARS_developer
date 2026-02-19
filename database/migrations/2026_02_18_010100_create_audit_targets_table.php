<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_targets', function (Blueprint $table) {
            $table->id();
            $table->string('business_name', 180)->nullable();
            $table->string('website_url', 255);
            $table->string('frequency', 20)->default('weekly');
            $table->string('status', 20)->default('active');
            $table->timestamp('next_run_at')->nullable();
            $table->timestamp('last_run_at')->nullable();
            $table->unsignedBigInteger('created_by_admin_user_id')->nullable();
            $table->timestamps();

            $table->unique('website_url');
            $table->index(['status', 'next_run_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_targets');
    }
};
