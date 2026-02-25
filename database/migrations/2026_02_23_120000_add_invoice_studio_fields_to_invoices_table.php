<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'public_token')) {
                $table->string('public_token', 80)->nullable()->unique()->after('client_invoice_number');
            }

            if (!Schema::hasColumn('invoices', 'invoice_payload')) {
                $table->longText('invoice_payload')->nullable()->after('notes');
            }

            if (!Schema::hasColumn('invoices', 'show_pay_button')) {
                $table->boolean('show_pay_button')->default(true)->after('invoice_payload');
            }

            if (!Schema::hasColumn('invoices', 'sent_to_email')) {
                $table->string('sent_to_email', 180)->nullable()->after('show_pay_button');
            }

            if (!Schema::hasColumn('invoices', 'sent_at')) {
                $table->timestamp('sent_at')->nullable()->after('sent_to_email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'sent_at')) {
                $table->dropColumn('sent_at');
            }

            if (Schema::hasColumn('invoices', 'sent_to_email')) {
                $table->dropColumn('sent_to_email');
            }

            if (Schema::hasColumn('invoices', 'show_pay_button')) {
                $table->dropColumn('show_pay_button');
            }

            if (Schema::hasColumn('invoices', 'invoice_payload')) {
                $table->dropColumn('invoice_payload');
            }

            if (Schema::hasColumn('invoices', 'public_token')) {
                $table->dropUnique('invoices_public_token_unique');
                $table->dropColumn('public_token');
            }
        });
    }
};
