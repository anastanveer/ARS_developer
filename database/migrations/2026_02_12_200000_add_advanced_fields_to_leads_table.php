<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'country')) {
                $table->string('country', 100)->nullable()->after('ip');
            }
            if (!Schema::hasColumn('leads', 'is_blocked')) {
                $table->boolean('is_blocked')->default(false)->after('status');
            }
            if (!Schema::hasColumn('leads', 'blocked_reason')) {
                $table->string('blocked_reason', 255)->nullable()->after('is_blocked');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            foreach (['country', 'is_blocked', 'blocked_reason'] as $col) {
                if (Schema::hasColumn('leads', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
