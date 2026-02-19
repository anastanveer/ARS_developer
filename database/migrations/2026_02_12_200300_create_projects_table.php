<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->string('title', 180);
            $table->string('type', 80)->nullable();
            $table->string('status', 40)->default('planning');
            $table->date('start_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->unsignedInteger('delivery_months')->default(3);
            $table->decimal('budget_total', 14, 2)->default(0);
            $table->decimal('paid_total', 14, 2)->default(0);
            $table->string('currency', 10)->default('GBP');
            $table->string('portal_token', 64)->unique();
            $table->longText('description')->nullable();
            $table->timestamps();
            $table->index(['status', 'delivery_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
