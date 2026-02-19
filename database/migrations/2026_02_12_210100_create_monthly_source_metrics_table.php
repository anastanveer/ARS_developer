<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('monthly_source_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monthly_metric_id')->constrained('monthly_metrics')->cascadeOnDelete();
            $table->string('source_name', 120);
            $table->unsignedInteger('leads_count')->default(0);
            $table->unsignedInteger('clients_count')->default(0);
            $table->decimal('sales_amount', 14, 2)->default(0);
            $table->timestamps();

            $table->index(['monthly_metric_id', 'source_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_source_metrics');
    }
};
