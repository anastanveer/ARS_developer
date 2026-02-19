<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_scan_runs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('audit_target_id')->nullable();
            $table->string('business_name', 180)->nullable();
            $table->string('website_url', 255);
            $table->unsignedTinyInteger('overall_score')->default(0);
            $table->unsignedTinyInteger('performance_score')->nullable();
            $table->unsignedTinyInteger('seo_score')->nullable();
            $table->unsignedTinyInteger('ux_score')->nullable();
            $table->unsignedTinyInteger('security_score')->nullable();
            $table->string('grade', 2)->nullable();
            $table->string('risk_level', 20)->nullable();
            $table->unsignedInteger('response_time_ms')->nullable();
            $table->json('findings_json')->nullable();
            $table->timestamp('scanned_at')->nullable();
            $table->unsignedBigInteger('created_by_admin_user_id')->nullable();
            $table->timestamps();

            $table->index(['website_url', 'scanned_at']);
            $table->index(['overall_score', 'created_at']);
            $table->index('audit_target_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_scan_runs');
    }
};
