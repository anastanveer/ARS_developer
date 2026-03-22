<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->constrained()->cascadeOnDelete();
            $table->string('full_name', 120);
            $table->string('email', 190);
            $table->string('website')->nullable();
            $table->longText('comment');
            $table->boolean('newsletter_opt_in')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->string('ip_address', 64)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['blog_post_id', 'is_approved']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_comments');
    }
};
