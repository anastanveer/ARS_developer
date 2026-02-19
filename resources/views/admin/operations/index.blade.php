@extends('admin.layout', ['title' => 'Operations & Audit'])

@section('content')
<div class="top">
    <h1 class="page-title">Operations & One-Click UK Audit</h1>
    <span class="pill">Finance + Hiring + Audit PDF</span>
</div>

<div class="grid" style="margin-bottom:14px">
    <div class="stat"><b>Total Revenue</b><span>GBP {{ number_format((float) $stats['total_revenue'], 0) }}</span></div>
    <div class="stat"><b>Project Budget</b><span>GBP {{ number_format((float) $stats['project_budget'], 0) }}</span></div>
    <div class="stat"><b>Outstanding Invoices</b><span>GBP {{ number_format((float) $stats['outstanding_invoices'], 0) }}</span></div>
    <div class="stat"><b>Total Expenses</b><span>GBP {{ number_format((float) $stats['expenses'], 0) }}</span></div>
    <div class="stat"><b>Monthly Team Cost</b><span>GBP {{ number_format((float) $stats['active_team_monthly'], 0) }}</span></div>
    <div class="stat"><b>One-Time Hiring Cost</b><span>GBP {{ number_format((float) $stats['hiring_one_time'], 0) }}</span></div>
    <div class="stat"><b>Net Snapshot</b><span>GBP {{ number_format((float) $stats['net_snapshot'], 0) }}</span></div>
    <div class="stat"><b>Est. Monthly Burn</b><span>GBP {{ number_format((float) $stats['monthly_runway'], 0) }}</span></div>
</div>

<div class="row" style="margin-bottom:14px">
    <div class="card">
        <div class="top">
            <h3 style="margin:0">One-Click UK Audit PDF</h3>
        </div>
        <p class="muted" style="margin:6px 0 12px">Default values are prefilled. Just click download to get UK-ready audit PDF for sharing with client.</p>
        <form action="{{ route('admin.operations.audit-pdf') }}" method="post" class="row3">
            @csrf
            <div><label>Company Name</label><input type="text" name="company_name" value="{{ old('company_name', config('company.legal_name', 'ARS Developer Ltd')) }}"></div>
            <div><label>Website URL</label><input type="text" name="website_url" value="{{ old('website_url', url('/')) }}"></div>
            <div><label>Recipient Name</label><input type="text" name="recipient_name" value="{{ old('recipient_name', 'UK Prospect Client') }}"></div>
            <div><label>Audit Focus</label><input type="text" name="audit_focus" value="{{ old('audit_focus', 'Business website growth readiness') }}"></div>
            <div class="full"><label>Primary Goal</label><input type="text" name="current_goal" value="{{ old('current_goal', 'Increase qualified UK leads and improve conversion') }}"></div>
            <div><label>Performance</label><input type="number" name="performance_score" min="40" max="100" value="{{ old('performance_score', 82) }}"></div>
            <div><label>SEO</label><input type="number" name="seo_score" min="40" max="100" value="{{ old('seo_score', 79) }}"></div>
            <div><label>UX</label><input type="number" name="ux_score" min="40" max="100" value="{{ old('ux_score', 81) }}"></div>
            <div><label>Security</label><input type="number" name="security_score" min="40" max="100" value="{{ old('security_score', 84) }}"></div>
            <div><label>Conversion</label><input type="number" name="conversion_score" min="40" max="100" value="{{ old('conversion_score', 77) }}"></div>
            <div class="full">
                <button type="submit" class="btn alt">Download Audit PDF</button>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="top">
            <h3 style="margin:0">Add Company Expense</h3>
        </div>
        <form action="{{ route('admin.operations.expenses.store') }}" method="post" class="row3">
            @csrf
            <div><label>Description</label><input type="text" name="description" required></div>
            <div><label>Category</label>
                <select name="category" required>
                    <option value="operations">Operations</option>
                    <option value="marketing">Marketing</option>
                    <option value="tooling">Tooling</option>
                    <option value="salary">Salary</option>
                    <option value="office">Office</option>
                    <option value="misc">Misc</option>
                </select>
            </div>
            <div><label>Amount (GBP)</label><input type="number" step="0.01" min="0.01" name="amount" required></div>
            <div><label>Expense Date</label><input type="date" name="expense_date" value="{{ now()->toDateString() }}" required></div>
            <div><label>Vendor</label><input type="text" name="vendor_name"></div>
            <div><label>Employee (optional)</label><input type="text" name="employee_name"></div>
            <div class="full"><label>Notes</label><textarea name="notes"></textarea></div>
            <div class="full"><button type="submit" class="btn">Add Expense</button></div>
        </form>
    </div>
</div>

<div class="row" style="margin-bottom:14px">
    <div class="card">
        <div class="top">
            <h3 style="margin:0">Add Team Hire / Budget</h3>
        </div>
        <form action="{{ route('admin.operations.team-hires.store') }}" method="post" class="row3">
            @csrf
            <div><label>Member Name</label><input type="text" name="name" required></div>
            <div><label>Role</label><input type="text" name="role" required></div>
            <div><label>Hire Date</label><input type="date" name="hired_on" value="{{ now()->toDateString() }}" required></div>
            <div><label>Monthly Cost (GBP)</label><input type="number" step="0.01" min="0" name="monthly_cost" required></div>
            <div><label>One-Time Cost (GBP)</label><input type="number" step="0.01" min="0" name="one_time_cost"></div>
            <div><label>Status</label>
                <select name="status" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="full"><label>Notes</label><textarea name="notes"></textarea></div>
            <div class="full"><button type="submit" class="btn">Add Team Hire</button></div>
        </form>
    </div>

    <div class="card">
        <div class="top">
            <h3 style="margin:0">Recent Project Finance Snapshot</h3>
        </div>
        <table>
            <thead><tr><th>Project</th><th>Client</th><th>Invoiced</th><th>Paid</th><th>Balance</th><th>Action</th></tr></thead>
            <tbody>
            @forelse($recentProjectFinance as $row)
                <tr>
                    <td>{{ $row['project']->title }}</td>
                    <td>{{ $row['project']->client?->name ?: '-' }}</td>
                    <td>{{ $row['project']->currency }} {{ number_format((float) $row['invoice_total'], 2) }}</td>
                    <td>{{ $row['project']->currency }} {{ number_format((float) $row['paid_total'], 2) }}</td>
                    <td>{{ $row['project']->currency }} {{ number_format((float) $row['balance'], 2) }}</td>
                    <td><a class="btn" href="{{ route('admin.projects.show', $row['project']) }}">Open</a></td>
                </tr>
            @empty
                <tr><td colspan="6">No projects available.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="card">
        <div class="top">
            <h3 style="margin:0">Latest Expenses</h3>
        </div>
        <table>
            <thead><tr><th>Date</th><th>Description</th><th>Category</th><th>Vendor</th><th>Amount</th><th>Action</th></tr></thead>
            <tbody>
            @forelse($expenses as $expense)
                <tr>
                    <td>{{ optional($expense->expense_date)->format('d M Y') }}</td>
                    <td>{{ $expense->description }}</td>
                    <td>{{ ucfirst($expense->category) }}</td>
                    <td>{{ $expense->vendor_name ?: '-' }}</td>
                    <td>GBP {{ number_format((float) $expense->amount, 2) }}</td>
                    <td>
                        <form class="inline" action="{{ route('admin.operations.expenses.delete', $expense) }}" method="post">
                            @csrf
                            <button type="submit" class="btn red">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">No expenses yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <div class="top">
            <h3 style="margin:0">Team Budget Tracking</h3>
        </div>
        <table>
            <thead><tr><th>Name</th><th>Role</th><th>Status</th><th>Monthly</th><th>One-Time</th><th>Action</th></tr></thead>
            <tbody>
            @forelse($teamHires as $hire)
                <tr>
                    <td>{{ $hire->name }}</td>
                    <td>{{ $hire->role }}</td>
                    <td>
                        <span class="pill">{{ strtoupper($hire->status) }}</span>
                        <form class="inline" action="{{ route('admin.operations.team-hires.status', $hire) }}" method="post">
                            @csrf
                            <input type="hidden" name="status" value="{{ $hire->status === 'active' ? 'inactive' : 'active' }}">
                            <button type="submit" class="btn gray">{{ $hire->status === 'active' ? 'Set Inactive' : 'Set Active' }}</button>
                        </form>
                    </td>
                    <td>GBP {{ number_format((float) $hire->monthly_cost, 2) }}</td>
                    <td>GBP {{ number_format((float) $hire->one_time_cost, 2) }}</td>
                    <td>
                        <form class="inline" action="{{ route('admin.operations.team-hires.delete', $hire) }}" method="post">
                            @csrf
                            <button type="submit" class="btn red">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">No team hires added yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
