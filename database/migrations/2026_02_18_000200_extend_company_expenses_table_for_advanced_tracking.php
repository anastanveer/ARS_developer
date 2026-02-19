<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('company_expenses')) {
            return;
        }

        Schema::table('company_expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('company_expenses', 'employee_name')) {
                $table->string('employee_name', 120)->nullable()->after('category');
            }
            if (!Schema::hasColumn('company_expenses', 'currency')) {
                $table->string('currency', 10)->default('GBP')->after('amount');
            }
            if (!Schema::hasColumn('company_expenses', 'project_id')) {
                $table->foreignId('project_id')->nullable()->after('currency')->constrained('projects')->nullOnDelete();
            }
            if (!Schema::hasColumn('company_expenses', 'created_by_admin_user_id')) {
                $table->unsignedBigInteger('created_by_admin_user_id')->nullable()->after('notes');
            }
        });

        Schema::table('company_expenses', function (Blueprint $table) {
            if (Schema::hasColumn('company_expenses', 'employee_name')) {
                $table->index(['employee_name', 'expense_date'], 'company_expenses_employee_date_idx');
            }
            if (Schema::hasColumn('company_expenses', 'created_by_admin_user_id')) {
                $table->index('created_by_admin_user_id', 'company_expenses_created_by_idx');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('company_expenses')) {
            return;
        }

        Schema::table('company_expenses', function (Blueprint $table) {
            if (Schema::hasColumn('company_expenses', 'project_id')) {
                $table->dropForeign(['project_id']);
                $table->dropColumn('project_id');
            }
            if (Schema::hasColumn('company_expenses', 'employee_name')) {
                $table->dropColumn('employee_name');
            }
            if (Schema::hasColumn('company_expenses', 'currency')) {
                $table->dropColumn('currency');
            }
            if (Schema::hasColumn('company_expenses', 'created_by_admin_user_id')) {
                $table->dropColumn('created_by_admin_user_id');
            }
        });
    }
};
