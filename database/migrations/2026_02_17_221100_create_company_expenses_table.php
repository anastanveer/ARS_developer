<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('company_expenses', function (Blueprint $table) {
            $table->id();
            $table->date('expense_date');
            $table->string('category', 60);
            $table->string('employee_name', 120)->nullable();
            $table->string('vendor_name', 120)->nullable();
            $table->string('description', 200);
            $table->decimal('amount', 14, 2);
            $table->string('currency', 10)->default('GBP');
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by_admin_user_id')->nullable();
            $table->timestamps();

            $table->index(['expense_date', 'category']);
            $table->index(['employee_name', 'expense_date']);
            $table->index('created_by_admin_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_expenses');
    }
};
