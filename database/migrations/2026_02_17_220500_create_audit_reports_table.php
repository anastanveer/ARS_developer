<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_reports', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 40)->unique();
            $table->string('business_name', 180);
            $table->string('website_url', 255);
            $table->string('recipient_name', 180)->nullable();
            $table->string('recipient_email', 180)->nullable();
            $table->unsignedTinyInteger('overall_score')->default(0);
            $table->unsignedTinyInteger('performance_score')->nullable();
            $table->unsignedTinyInteger('seo_score')->nullable();
            $table->unsignedTinyInteger('ux_score')->nullable();
            $table->unsignedTinyInteger('security_score')->nullable();
            $table->text('summary')->nullable();
            $table->text('strengths')->nullable();
            $table->text('issues')->nullable();
            $table->longText('recommendations')->nullable();
            $table->string('estimated_timeline', 120)->nullable();
            $table->unsignedBigInteger('created_by_admin_user_id')->nullable();
            $table->timestamps();

            $table->index(['created_at', 'overall_score']);
            $table->index('created_by_admin_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_reports');
    }
};
