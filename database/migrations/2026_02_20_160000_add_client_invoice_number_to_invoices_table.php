<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('invoices', 'client_invoice_number')) {
                $table->string('client_invoice_number', 80)->nullable()->after('invoice_number');
                $table->index(['project_id', 'client_invoice_number'], 'invoices_project_client_invoice_idx');
            }
        });

        $rows = DB::table('invoices')
            ->join('projects', 'projects.id', '=', 'invoices.project_id')
            ->select('invoices.id', 'invoices.project_id', 'projects.client_id')
            ->orderBy('invoices.id')
            ->get();

        $counters = [];
        foreach ($rows as $row) {
            $projectId = (int) ($row->project_id ?? 0);
            $clientId = (int) ($row->client_id ?? 0);
            if ($projectId <= 0 || $clientId <= 0) {
                continue;
            }

            $counters[$projectId] = ($counters[$projectId] ?? 0) + 1;
            $value = 'CL-' . $clientId . '-' . now()->format('Y') . '-' . str_pad((string) $counters[$projectId], 4, '0', STR_PAD_LEFT);

            DB::table('invoices')
                ->where('id', (int) $row->id)
                ->whereNull('client_invoice_number')
                ->update(['client_invoice_number' => $value]);
        }
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            if (Schema::hasColumn('invoices', 'client_invoice_number')) {
                $table->dropIndex('invoices_project_client_invoice_idx');
                $table->dropColumn('client_invoice_number');
            }
        });
    }
};
