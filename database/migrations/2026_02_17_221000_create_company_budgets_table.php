<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('company_budgets', function (Blueprint $table) {
            $table->id();
            $table->date('budget_month');
            $table->string('department', 90);
            $table->decimal('budget_amount', 14, 2)->default(0);
            $table->string('currency', 10)->default('GBP');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by_admin_user_id')->nullable();
            $table->timestamps();

            $table->unique(['budget_month', 'department']);
            $table->index(['budget_month', 'created_at']);
            $table->index('created_by_admin_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_budgets');
    }
};
