<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyBudget;
use App\Models\CompanyExpense;
use App\Models\Payment;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FinanceController extends Controller
{
    public function index(Request $request): View
    {
        $monthInput = trim((string) $request->query('month', now()->format('Y-m')));
        $selectedMonth = $this->resolveMonth($monthInput);
        $monthQuery = $selectedMonth->format('Y-m');
        $monthStart = $selectedMonth->copy()->startOfMonth()->toDateString();
        $monthEnd = $selectedMonth->copy()->endOfMonth()->toDateString();

        $categoryFilter = trim((string) $request->query('category', ''));
        $search = trim((string) $request->query('q', ''));

        $expensesQuery = CompanyExpense::query()
            ->with(['project.client'])
            ->whereBetween('expense_date', [$monthStart, $monthEnd])
            ->orderByDesc('expense_date')
            ->orderByDesc('id');

        if ($categoryFilter !== '') {
            $expensesQuery->where('category', $categoryFilter);
        }

        if ($search !== '') {
            $expensesQuery->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('employee_name', 'like', "%{$search}%")
                    ->orWhere('vendor_name', 'like', "%{$search}%")
                    ->orWhereHas('project', function ($projectQ) use ($search) {
                        $projectQ->where('title', 'like', "%{$search}%");
                    });
            });
        }

        $expenses = $expensesQuery->paginate(20)->withQueryString();

        $monthRevenue = (float) Payment::query()
            ->whereBetween('payment_date', [$monthStart, $monthEnd])
            ->sum('amount');

        $allRevenue = (float) Payment::query()->sum('amount');

        $monthExpensesTotal = (float) CompanyExpense::query()
            ->whereBetween('expense_date', [$monthStart, $monthEnd])
            ->sum('amount');

        $allExpenses = (float) CompanyExpense::query()->sum('amount');

        $monthBudgets = CompanyBudget::query()
            ->whereDate('budget_month', $selectedMonth->copy()->startOfMonth()->toDateString())
            ->orderBy('department')
            ->get();

        $monthBudgetTotal = (float) $monthBudgets->sum('budget_amount');

        $employeeSpend = CompanyExpense::query()
            ->selectRaw("COALESCE(NULLIF(TRIM(employee_name), ''), 'Unassigned') as employee, SUM(amount) as total")
            ->whereBetween('expense_date', [$monthStart, $monthEnd])
            ->groupBy('employee')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        $categorySpend = CompanyExpense::query()
            ->selectRaw('category, SUM(amount) as total')
            ->whereBetween('expense_date', [$monthStart, $monthEnd])
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        $projects = Project::query()->with('client')->orderByDesc('id')->limit(120)->get();

        $stats = [
            'month_revenue' => $monthRevenue,
            'month_expenses' => $monthExpensesTotal,
            'month_net' => $monthRevenue - $monthExpensesTotal,
            'month_budget' => $monthBudgetTotal,
            'month_budget_remaining' => $monthBudgetTotal - $monthExpensesTotal,
            'team_spend_month' => (float) CompanyExpense::query()
                ->whereBetween('expense_date', [$monthStart, $monthEnd])
                ->where(function ($q) {
                    $q->whereIn('category', ['salary', 'hiring', 'contractor'])
                        ->orWhereNotNull('employee_name');
                })
                ->sum('amount'),
            'all_revenue' => $allRevenue,
            'all_expenses' => $allExpenses,
            'all_net' => $allRevenue - $allExpenses,
        ];

        return view('admin.finance.index', compact(
            'stats',
            'monthQuery',
            'selectedMonth',
            'expenses',
            'monthBudgets',
            'employeeSpend',
            'categorySpend',
            'projects',
            'categoryFilter',
            'search'
        ));
    }

    public function storeExpense(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'expense_date' => ['required', 'date'],
            'category' => ['required', 'string', 'max:60'],
            'employee_name' => ['nullable', 'string', 'max:120'],
            'vendor_name' => ['nullable', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:200'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['nullable', 'string', 'max:10'],
            'project_id' => ['nullable', 'integer', 'exists:projects,id'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        CompanyExpense::query()->create([
            ...$data,
            'currency' => strtoupper(trim((string) ($data['currency'] ?? 'GBP'))) ?: 'GBP',
            'created_by_admin_user_id' => (int) $request->session()->get('admin_user_id', 0) ?: null,
        ]);

        return back()->with('success', 'Expense added successfully.');
    }

    public function storeBudget(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'budget_month' => ['required', 'date_format:Y-m'],
            'department' => ['required', 'string', 'max:90'],
            'budget_amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:10'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $monthDate = Carbon::createFromFormat('Y-m', $data['budget_month'])->startOfMonth()->toDateString();

        CompanyBudget::query()->updateOrCreate(
            [
                'budget_month' => $monthDate,
                'department' => trim((string) $data['department']),
            ],
            [
                'budget_amount' => (float) $data['budget_amount'],
                'currency' => strtoupper(trim((string) ($data['currency'] ?? 'GBP'))) ?: 'GBP',
                'notes' => trim((string) ($data['notes'] ?? '')),
                'created_by_admin_user_id' => (int) $request->session()->get('admin_user_id', 0) ?: null,
            ]
        );

        return back()->with('success', 'Budget saved for selected month.');
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $monthInput = trim((string) $request->query('month', now()->format('Y-m')));
        $selectedMonth = $this->resolveMonth($monthInput);
        $monthStart = $selectedMonth->copy()->startOfMonth()->toDateString();
        $monthEnd = $selectedMonth->copy()->endOfMonth()->toDateString();

        $rows = CompanyExpense::query()
            ->with(['project.client'])
            ->whereBetween('expense_date', [$monthStart, $monthEnd])
            ->orderBy('expense_date')
            ->orderBy('id')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="finance-expenses-' . $selectedMonth->format('Y-m') . '.csv"',
        ];

        return response()->stream(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date', 'Category', 'Employee', 'Vendor', 'Description', 'Project', 'Client', 'Amount', 'Currency', 'Notes']);

            foreach ($rows as $expense) {
                fputcsv($out, [
                    optional($expense->expense_date)->format('Y-m-d'),
                    $expense->category,
                    $expense->employee_name,
                    $expense->vendor_name,
                    $expense->description,
                    $expense->project?->title,
                    $expense->project?->client?->name,
                    number_format((float) $expense->amount, 2, '.', ''),
                    $expense->currency,
                    $expense->notes,
                ]);
            }

            fclose($out);
        }, 200, $headers);
    }

    private function resolveMonth(string $monthInput): Carbon
    {
        try {
            return Carbon::createFromFormat('Y-m', $monthInput)->startOfMonth();
        } catch (\Throwable) {
            return now()->startOfMonth();
        }
    }
}
