<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('client_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete();
            $table->string('review_token', 80)->unique();
            $table->string('reviewer_name', 140)->nullable();
            $table->string('reviewer_email', 190)->nullable();
            $table->string('company_name', 180)->nullable();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->string('review_title', 160)->nullable();
            $table->text('review_text')->nullable();
            $table->string('result_summary', 220)->nullable();
            $table->boolean('is_approved')->default(false);
            $table->foreignId('approved_by_admin_user_id')->nullable()->constrained('admin_users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('email_sent_at')->nullable();
            $table->ipAddress('submitted_ip')->nullable();
            $table->string('submitted_country', 8)->nullable();
            $table->timestamps();

            $table->index(['is_approved', 'approved_at']);
            $table->index(['project_id', 'invoice_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_reviews');
    }
};

