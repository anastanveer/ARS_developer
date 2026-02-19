@extends('admin.layout', ['title' => 'Finance Control'])

@section('content')
<div class="top">
    <h1 class="page-title">Finance Control Center</h1>
    <a href="{{ route('admin.finance.export', ['month' => $monthQuery]) }}" class="btn alt">Export Month CSV</a>
</div>

<form method="get" class="row3" style="margin-bottom:12px;">
    <div>
        <label>Month</label>
        <input type="month" name="month" value="{{ $monthQuery }}">
    </div>
    <div>
        <label>Category</label>
        <input type="text" name="category" value="{{ $categoryFilter }}" placeholder="operations / salary / marketing">
    </div>
    <div>
        <label>Search</label>
        <input type="text" name="q" value="{{ $search }}" placeholder="employee, vendor, project">
    </div>
    <div class="full">
        <button class="btn" type="submit">Apply Filters</button>
    </div>
</form>

<div class="grid" style="margin-bottom:14px">
    <div class="stat"><b>Month Revenue</b><span>GBP {{ number_format((float) $stats['month_revenue'], 2) }}</span></div>
    <div class="stat"><b>Month Expenses</b><span>GBP {{ number_format((float) $stats['month_expenses'], 2) }}</span></div>
    <div class="stat"><b>Month Net</b><span>GBP {{ number_format((float) $stats['month_net'], 2) }}</span></div>
    <div class="stat"><b>Month Budget</b><span>GBP {{ number_format((float) $stats['month_budget'], 2) }}</span></div>
    <div class="stat"><b>Budget Remaining</b><span>GBP {{ number_format((float) $stats['month_budget_remaining'], 2) }}</span></div>
    <div class="stat"><b>Team Spend</b><span>GBP {{ number_format((float) $stats['team_spend_month'], 2) }}</span></div>
    <div class="stat"><b>All-time Revenue</b><span>GBP {{ number_format((float) $stats['all_revenue'], 2) }}</span></div>
    <div class="stat"><b>All-time Net</b><span>GBP {{ number_format((float) $stats['all_net'], 2) }}</span></div>
</div>

<div class="row" style="margin-bottom:14px;">
    <div class="card">
        <h3 style="margin-top:0;">Add Expense Entry</h3>
        <form action="{{ route('admin.finance.expense.store') }}" method="post" class="row3">
            @csrf
            <div><label>Date</label><input type="date" name="expense_date" value="{{ now()->toDateString() }}" required></div>
            <div><label>Category</label><input type="text" name="category" value="operations" required></div>
            <div><label>Employee</label><input type="text" name="employee_name"></div>
            <div><label>Vendor</label><input type="text" name="vendor_name"></div>
            <div><label>Description</label><input type="text" name="description" required></div>
            <div><label>Amount</label><input type="number" step="0.01" min="0.01" name="amount" required></div>
            <div><label>Currency</label><input type="text" name="currency" value="GBP"></div>
            <div><label>Project</label>
                <select name="project_id">
                    <option value="">-- None --</option>
                    @foreach($projects as $project)
                        <option value="{{ $project->id }}">{{ $project->title }} ({{ $project->client?->name ?: '-' }})</option>
                    @endforeach
                </select>
            </div>
            <div class="full"><label>Notes</label><textarea name="notes"></textarea></div>
            <div class="full"><button class="btn" type="submit">Save Expense</button></div>
        </form>
    </div>

    <div class="card">
        <h3 style="margin-top:0;">Set Department Budget</h3>
        <form action="{{ route('admin.finance.budget.store') }}" method="post" class="row3">
            @csrf
            <div><label>Budget Month</label><input type="month" name="budget_month" value="{{ $monthQuery }}" required></div>
            <div><label>Department</label><input type="text" name="department" placeholder="SEO / Development / Operations" required></div>
            <div><label>Budget Amount</label><input type="number" step="0.01" min="0" name="budget_amount" required></div>
            <div><label>Currency</label><input type="text" name="currency" value="GBP"></div>
            <div class="full"><label>Notes</label><textarea name="notes"></textarea></div>
            <div class="full"><button class="btn alt" type="submit">Save Budget</button></div>
        </form>
        <hr style="border:0;border-top:1px solid #e4eaf8;margin:14px 0;">
        <h4 style="margin:0 0 8px;">Current Month Budgets</h4>
        <table>
            <thead><tr><th>Department</th><th>Budget</th><th>Notes</th></tr></thead>
            <tbody>
            @forelse($monthBudgets as $b)
                <tr>
                    <td>{{ $b->department }}</td>
                    <td>{{ $b->currency }} {{ number_format((float) $b->budget_amount, 2) }}</td>
                    <td>{{ $b->notes ?: '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No budgets for selected month.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <h3 style="margin-top:0;">Expense Ledger ({{ $selectedMonth->format('F Y') }})</h3>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Employee</th>
                <th>Vendor</th>
                <th>Description</th>
                <th>Project</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $e)
                <tr>
                    <td>{{ optional($e->expense_date)->format('d M Y') }}</td>
                    <td>{{ $e->category }}</td>
                    <td>{{ $e->employee_name ?: '-' }}</td>
                    <td>{{ $e->vendor_name ?: '-' }}</td>
                    <td>{{ $e->description }}</td>
                    <td>{{ $e->project?->title ?: '-' }}</td>
                    <td>{{ $e->currency }} {{ number_format((float) $e->amount, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="7">No expenses found for selected filters.</td></tr>
            @endforelse
        </tbody>
    </table>
    <div style="margin-top:10px;">{{ $expenses->links() }}</div>
</div>
@endsection

