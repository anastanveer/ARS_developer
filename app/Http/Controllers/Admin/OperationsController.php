<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyExpense;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Project;
use App\Models\TeamHire;
use App\Support\SimplePdfReport;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OperationsController extends Controller
{
    public function index(): View
    {
        $totalRevenue = (float) Payment::query()->sum('amount');
        $totalProjectBudget = (float) Project::query()->sum('budget_total');
        $totalExpense = (float) CompanyExpense::query()->sum('amount');
        $activeTeamMonthlyCost = (float) TeamHire::query()
            ->where('status', 'active')
            ->sum('monthly_cost');
        $oneTimeHiringCost = (float) TeamHire::query()->sum('one_time_cost');

        $outstandingInvoiceAmount = (float) Invoice::query()
            ->whereIn('status', ['unpaid', 'partially_paid', 'overdue'])
            ->selectRaw('COALESCE(SUM(amount - paid_amount), 0) as balance')
            ->value('balance');

        $netSnapshot = $totalRevenue - $totalExpense - $oneTimeHiringCost;
        $driver = DB::connection()->getDriverName();
        $monthExpr = $driver === 'sqlite'
            ? "strftime('%Y-%m', expense_date)"
            : "DATE_FORMAT(expense_date, '%Y-%m')";

        $activeExpenseMonths = (int) CompanyExpense::query()
            ->whereNotNull('expense_date')
            ->selectRaw("COUNT(DISTINCT {$monthExpr}) as cnt")
            ->value('cnt');

        $monthlyRunway = $activeTeamMonthlyCost + ($totalExpense / max(1, $activeExpenseMonths));

        $expenses = CompanyExpense::query()->latest('expense_date')->latest()->limit(12)->get();
        $teamHires = TeamHire::query()->latest('hired_on')->latest()->limit(12)->get();

        $recentProjectFinance = Project::query()
            ->with(['client', 'invoices', 'payments'])
            ->latest()
            ->limit(10)
            ->get()
            ->map(function (Project $project) {
                $invoiceTotal = (float) $project->invoices->sum('amount');
                $paidTotal = (float) $project->payments->sum('amount');
                return [
                    'project' => $project,
                    'invoice_total' => $invoiceTotal,
                    'paid_total' => $paidTotal,
                    'balance' => max(0, $invoiceTotal - $paidTotal),
                ];
            });

        return view('admin.operations.index', [
            'stats' => [
                'total_revenue' => $totalRevenue,
                'project_budget' => $totalProjectBudget,
                'outstanding_invoices' => $outstandingInvoiceAmount,
                'expenses' => $totalExpense,
                'active_team_monthly' => $activeTeamMonthlyCost,
                'hiring_one_time' => $oneTimeHiringCost,
                'net_snapshot' => $netSnapshot,
                'monthly_runway' => $monthlyRunway,
            ],
            'expenses' => $expenses,
            'teamHires' => $teamHires,
            'recentProjectFinance' => $recentProjectFinance,
        ]);
    }

    public function storeExpense(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'description' => ['required', 'string', 'max:200'],
            'category' => ['required', 'in:operations,marketing,tooling,salary,office,misc'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'expense_date' => ['required', 'date'],
            'vendor_name' => ['nullable', 'string', 'max:120'],
            'employee_name' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string'],
        ]);

        CompanyExpense::query()->create([
            ...$data,
            'currency' => 'GBP',
            'created_by_admin_user_id' => (int) $request->session()->get('admin_user_id', 0) ?: null,
        ]);

        return back()->with('success', 'Expense added successfully.');
    }

    public function destroyExpense(CompanyExpense $expense): RedirectResponse
    {
        $expense->delete();

        return back()->with('success', 'Expense deleted.');
    }

    public function storeTeamHire(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'role' => ['required', 'string', 'max:120'],
            'monthly_cost' => ['required', 'numeric', 'min:0'],
            'one_time_cost' => ['nullable', 'numeric', 'min:0'],
            'hired_on' => ['required', 'date'],
            'status' => ['required', 'in:active,inactive'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['one_time_cost'] = $data['one_time_cost'] ?? 0;
        TeamHire::query()->create($data);

        return back()->with('success', 'Team hire added.');
    }

    public function updateTeamHireStatus(Request $request, TeamHire $teamHire): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:active,inactive'],
        ]);

        $teamHire->update($data);
        return back()->with('success', 'Team member status updated.');
    }

    public function destroyTeamHire(TeamHire $teamHire): RedirectResponse
    {
        $teamHire->delete();

        return back()->with('success', 'Team hire deleted.');
    }

    public function downloadUkAuditPdf(Request $request): Response
    {
        $validated = $request->validate([
            'company_name' => ['nullable', 'string', 'max:150'],
            'website_url' => ['nullable', 'string', 'max:190'],
            'recipient_name' => ['nullable', 'string', 'max:120'],
            'audit_focus' => ['nullable', 'string', 'max:150'],
            'current_goal' => ['nullable', 'string', 'max:220'],
            'performance_score' => ['nullable', 'integer', 'between:40,100'],
            'seo_score' => ['nullable', 'integer', 'between:40,100'],
            'ux_score' => ['nullable', 'integer', 'between:40,100'],
            'security_score' => ['nullable', 'integer', 'between:40,100'],
            'conversion_score' => ['nullable', 'integer', 'between:40,100'],
        ]);

        $companyName = trim((string) ($validated['company_name'] ?? config('company.legal_name', 'ARS Developer Ltd')));
        $issuerLegalName = (string) config('company.legal_name', 'ARS Developer Ltd');
        $issuerCompanyNumber = (string) config('company.company_number', '17039150');
        $issuerRegisteredIn = (string) config('company.registered_in', 'England & Wales');
        $websiteUrl = trim((string) ($validated['website_url'] ?? url('/')));
        $recipient = trim((string) ($validated['recipient_name'] ?? 'Prospective UK Client'));
        $focus = trim((string) ($validated['audit_focus'] ?? 'Business website growth readiness'));
        $goal = trim((string) ($validated['current_goal'] ?? 'Increase qualified UK leads and improve conversion rate'));

        $scores = [
            'Performance' => (int) ($validated['performance_score'] ?? 82),
            'SEO Visibility' => (int) ($validated['seo_score'] ?? 79),
            'UX Clarity' => (int) ($validated['ux_score'] ?? 81),
            'Security & Trust' => (int) ($validated['security_score'] ?? 84),
            'Conversion Readiness' => (int) ($validated['conversion_score'] ?? 77),
        ];

        $avg = (int) round(collect($scores)->avg());
        $revenue = (float) Payment::query()->sum('amount');
        $expenses = (float) CompanyExpense::query()->sum('amount');
        $activeTeam = (int) TeamHire::query()->where('status', 'active')->count();
        $openInvoices = (int) Invoice::query()->whereIn('status', ['unpaid', 'partially_paid', 'overdue'])->count();

        $today = Carbon::now()->format('d M Y');
        $month = Carbon::now()->format('F Y');
        $filename = 'uk-audit-' . Carbon::now()->format('Ymd-Hi') . '.pdf';

        $lines = [
            $issuerLegalName . ' - One Click Audit Report',
            'Generated: ' . $today,
            ' ',
            'Recipient: ' . $recipient,
            'Company: ' . $companyName,
            'Website: ' . $websiteUrl,
            'Audit Focus: ' . $focus,
            'Primary Goal: ' . $goal,
            ' ',
            'Overall UK Readiness Score: ' . $avg . '/100',
            ' ',
            'Category Scores',
        ];

        foreach ($scores as $label => $score) {
            $lines[] = '- ' . $label . ': ' . $score . '/100';
        }

        $lines = array_merge($lines, [
            ' ',
            'Priority Findings (Business Friendly)',
            '- Strengthen local intent pages for UK city and service combinations.',
            '- Improve trust blocks: testimonials, case snapshots, and response SLA.',
            '- Tighten conversion flow with one primary action per page section.',
            '- Add recurring technical checks for speed, broken links, and forms.',
            ' ',
            'Recommended 30 Day Action Plan',
            'Week 1: Technical cleanup and conversion tracking baseline.',
            'Week 2: Service page clarity + local SEO intent alignment.',
            'Week 3: UX improvements for contact, quote, and booking steps.',
            'Week 4: Review KPIs and launch priority growth backlog.',
            ' ',
            'Internal Operations Snapshot (' . $month . ')',
            '- Total Revenue Logged: GBP ' . number_format($revenue, 2),
            '- Total Company Expenses: GBP ' . number_format($expenses, 2),
            '- Active Team Members: ' . $activeTeam,
            '- Open Invoices: ' . $openInvoices,
            ' ',
            'Prepared by ' . $issuerLegalName . ' Operations Desk',
            'Company No: ' . $issuerCompanyNumber,
            'Registered in ' . $issuerRegisteredIn,
            'This report is generated as a quick decision document for UK business discussions.',
        ]);

        $pdf = SimplePdfReport::buildFromLines($lines);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        ]);
    }
}
