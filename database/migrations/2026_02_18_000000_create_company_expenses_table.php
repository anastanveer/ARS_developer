<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('company_expenses')) {
            return;
        }

        Schema::create('company_expenses', function (Blueprint $table) {
            $table->id();
            $table->string('title', 180);
            $table->string('category', 60)->default('operations');
            $table->decimal('amount', 14, 2);
            $table->date('expense_date');
            $table->string('vendor', 120)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['category', 'expense_date']);
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('company_expenses')) {
            return;
        }

        Schema::dropIfExists('company_expenses');
    }
};
