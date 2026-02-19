<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'coupon_code')) {
                $table->string('coupon_code', 40)->nullable()->after('budget_range');
            }
            if (!Schema::hasColumn('leads', 'coupon_discount')) {
                $table->decimal('coupon_discount', 12, 2)->nullable()->after('coupon_code');
            }
            if (!Schema::hasColumn('leads', 'quote_final_preview')) {
                $table->decimal('quote_final_preview', 12, 2)->nullable()->after('coupon_discount');
            }
            if (!Schema::hasColumn('leads', 'coupon_validated')) {
                $table->boolean('coupon_validated')->default(false)->after('quote_final_preview');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            foreach (['coupon_validated', 'quote_final_preview', 'coupon_discount', 'coupon_code'] as $column) {
                if (Schema::hasColumn('leads', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

