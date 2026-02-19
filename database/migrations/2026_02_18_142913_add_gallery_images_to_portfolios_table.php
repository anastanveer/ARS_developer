<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->string('image_path_2', 255)->nullable()->after('image_path');
            $table->string('image_path_3', 255)->nullable()->after('image_path_2');
        });
    }

    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn(['image_path_2', 'image_path_3']);
        });
    }
};
