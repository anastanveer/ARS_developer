<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('monthly_metrics', function (Blueprint $table) {
            $table->id();
            $table->date('month');
            $table->decimal('sales_amount', 14, 2)->default(0);
            $table->decimal('work_value', 14, 2)->default(0);
            $table->unsignedInteger('new_clients_count')->default(0);
            $table->unsignedInteger('leads_count')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique('month');
            $table->index('month');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_metrics');
    }
};
