<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Lead;
use App\Models\MonthlyMetric;
use App\Models\MonthlySourceMetric;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(): View
    {
        $metrics = MonthlyMetric::query()
            ->with('sourceMetrics')
            ->orderBy('month')
            ->get();

        $totals = [
            'sales' => (float) $metrics->sum('sales_amount'),
            'work' => (float) $metrics->sum('work_value'),
            'clients' => (int) $metrics->sum('new_clients_count'),
            'leads' => (int) $metrics->sum('leads_count'),
            'payments_logged' => (float) Payment::sum('amount'),
        ];

        $thisYearSales = (float) MonthlyMetric::query()
            ->whereYear('month', now()->year)
            ->sum('sales_amount');

        $salesByMonth = $metrics->map(function (MonthlyMetric $metric) {
            return [
                'label' => $metric->month->format('M y'),
                'value' => (float) $metric->sales_amount,
            ];
        })->values()->all();

        $clientsByMonth = $metrics->map(function (MonthlyMetric $metric) {
            return [
                'label' => $metric->month->format('M y'),
                'value' => (int) $metric->new_clients_count,
            ];
        })->values()->all();

        $autoLeadCountries = Lead::query()
            ->select('country', DB::raw('COUNT(*) as total'))
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $autoLeadSources = Lead::query()
            ->select('submitted_from')
            ->whereNotNull('submitted_from')
            ->where('submitted_from', '!=', '')
            ->get()
            ->map(function ($lead) {
                $host = parse_url((string) $lead->submitted_from, PHP_URL_HOST);
                return $host ?: 'direct';
            })
            ->countBy()
            ->sortDesc()
            ->take(8);

        $latestMetric = $metrics->last();
        $latestSourceMetrics = $latestMetric ? $latestMetric->sourceMetrics : collect();

        $monthlyAuto = collect(range(0, 11))->map(function (int $back) {
            $date = now()->subMonths(11 - $back);
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            return [
                'label' => $date->format('M y'),
                'leads' => Lead::query()->whereBetween('created_at', [$start, $end])->count(),
                'clients' => Client::query()->whereBetween('created_at', [$start, $end])->count(),
            ];
        });

        return view('admin.analytics.index', compact(
            'metrics',
            'totals',
            'thisYearSales',
            'salesByMonth',
            'clientsByMonth',
            'autoLeadCountries',
            'autoLeadSources',
            'latestMetric',
            'latestSourceMetrics',
            'monthlyAuto'
        ));
    }

    public function storeMonthlyMetric(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'month' => ['required', 'date_format:Y-m'],
            'sales_amount' => ['required', 'numeric', 'min:0'],
            'work_value' => ['required', 'numeric', 'min:0'],
            'new_clients_count' => ['required', 'integer', 'min:0'],
            'leads_count' => ['required', 'integer', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $monthDate = Carbon::createFromFormat('Y-m', $data['month'])->startOfMonth()->toDateString();

        MonthlyMetric::query()->updateOrCreate(
            ['month' => $monthDate],
            [
                'sales_amount' => $data['sales_amount'],
                'work_value' => $data['work_value'],
                'new_clients_count' => $data['new_clients_count'],
                'leads_count' => $data['leads_count'],
                'notes' => $data['notes'] ?? null,
            ]
        );

        return back()->with('success', 'Monthly analytics saved successfully.');
    }

    public function storeSourceMetric(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'monthly_metric_id' => ['required', 'exists:monthly_metrics,id'],
            'source_name' => ['required', 'string', 'max:120'],
            'leads_count' => ['required', 'integer', 'min:0'],
            'clients_count' => ['required', 'integer', 'min:0'],
            'sales_amount' => ['required', 'numeric', 'min:0'],
        ]);

        MonthlySourceMetric::query()->create($data);

        return back()->with('success', 'Source-wise metric added.');
    }
}
