<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blocked_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('email', 180)->nullable();
            $table->string('ip', 64)->nullable();
            $table->string('reason', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['email', 'is_active']);
            $table->index(['ip', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocked_contacts');
    }
};
