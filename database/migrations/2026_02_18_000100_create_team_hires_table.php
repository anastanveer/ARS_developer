<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('team_hires')) {
            return;
        }

        Schema::create('team_hires', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('role', 120);
            $table->decimal('monthly_cost', 14, 2)->default(0);
            $table->decimal('one_time_cost', 14, 2)->default(0);
            $table->date('hired_on');
            $table->string('status', 30)->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(['status', 'hired_on']);
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('team_hires')) {
            return;
        }

        Schema::dropIfExists('team_hires');
    }
};
