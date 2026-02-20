<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Client Portal - {{ $project->title }}</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="canonical" href="{{ route('client.portal', ['token' => $project->portal_token]) }}">
    <style>
        :root{
            --primary:#0f2f6f;
            --accent:#1f8de7;
            --mint:#22d7b8;
            --warning:#f59e0b;
            --text:#172b4d;
            --muted:#5e738f;
            --line:#dbe6f9;
            --bg:#eef4ff;
            --card:#ffffff;
            --ok-bg:#e8fff4;
            --ok-text:#0d6f43;
            --er-bg:#ffecec;
            --er-text:#9b1a1a;
        }
        *{box-sizing:border-box}
        body{
            margin:0;
            font-family:"DM Sans",Arial,sans-serif;
            color:var(--text);
            background:
                radial-gradient(1200px 400px at 8% -20%, rgba(34,215,184,.14), transparent 55%),
                radial-gradient(1200px 500px at 95% -20%, rgba(31,141,231,.16), transparent 55%),
                var(--bg);
        }
        .shell{max-width:1240px;margin:0 auto;padding:30px 18px 44px}
        .hero{
            background:linear-gradient(130deg,var(--primary),#163f8f 45%, var(--accent));
            color:#fff;
            border-radius:24px;
            padding:26px 26px 22px;
            box-shadow:0 24px 40px rgba(17,44,90,.18);
            margin-bottom:20px;
        }
        .hero-top{display:flex;justify-content:space-between;gap:14px;flex-wrap:wrap;align-items:flex-start}
        .hero h1{margin:0 0 6px;font-size:30px;line-height:1.1}
        .hero .sub{margin:0;color:rgba(255,255,255,.86)}
        .hero-nav{display:flex;gap:8px;flex-wrap:wrap;margin-top:14px}
        .quick-link{
            display:inline-flex;align-items:center;justify-content:center;
            background:rgba(255,255,255,.12);
            border:1px solid rgba(255,255,255,.28);
            color:#fff;text-decoration:none;
            padding:8px 12px;border-radius:999px;font-size:13px;font-weight:600;
        }
        .quick-link:hover{background:rgba(255,255,255,.2)}
        .status-chip{
            display:inline-flex;align-items:center;gap:8px;
            border:1px solid rgba(255,255,255,.32);
            background:rgba(0,0,0,.12);
            border-radius:999px;padding:8px 12px;font-size:13px;
        }
        .status-dot{width:8px;height:8px;border-radius:50%;background:#4cffbf}
        .flash{padding:11px 12px;border-radius:12px;margin-bottom:14px;font-weight:600;border:1px solid transparent}
        .flash.ok{background:var(--ok-bg);color:var(--ok-text);border-color:#b8efd4}
        .flash.er{background:var(--er-bg);color:var(--er-text);border-color:#ffd2d2}
        .kpis{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin-bottom:18px}
        .kpi{
            background:var(--card);border:1px solid var(--line);
            border-radius:14px;padding:14px 13px;
            box-shadow:0 7px 18px rgba(13,49,106,.06);
            transition:transform .2s ease, box-shadow .2s ease;
        }
        .kpi:hover{transform:translateY(-2px);box-shadow:0 14px 25px rgba(13,49,106,.09)}
        .kpi b{display:block;font-size:12px;color:var(--muted);font-weight:700;letter-spacing:.04em;text-transform:uppercase}
        .kpi span{display:block;font-size:26px;font-weight:800;line-height:1.1;margin-top:6px;color:var(--primary)}
        .kpi small{color:var(--muted)}
        .layout{display:grid;grid-template-columns:1.05fr .95fr;gap:16px}
        .card{
            background:var(--card);border:1px solid var(--line);border-radius:16px;
            padding:20px;box-shadow:0 8px 18px rgba(13,49,106,.05);margin-bottom:16px;
        }
        .card h2,.card h3{margin:0 0 10px;font-size:20px;color:var(--primary)}
        .card h3{font-size:18px}
        .section-sub{margin:-3px 0 12px;color:var(--muted);font-size:14px}
        .muted{color:var(--muted)}
        .progress-meta{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:8px;margin-bottom:10px}
        .mini{
            border:1px solid #dfeafb;border-radius:12px;padding:10px;background:#f8fbff;
            font-size:13px;color:var(--muted);
        }
        .mini strong{display:block;color:var(--primary);font-size:16px;margin-top:4px}
        .progress-wrap{height:12px;border-radius:999px;overflow:hidden;background:#e6efff}
        .progress-bar{height:100%;background:linear-gradient(90deg,var(--accent),var(--mint))}
        .progress-label{margin:8px 0 0;font-size:13px;color:var(--muted);font-weight:600}
        .list{margin:0;padding:0;list-style:none;display:grid;gap:10px}
        .list li{
            border:1px solid #e3ecfb;background:#f9fbff;border-radius:12px;
            padding:10px 11px;
        }
        .list-top{display:flex;justify-content:space-between;gap:10px;align-items:flex-start}
        .tag{
            display:inline-block;padding:4px 9px;border-radius:999px;
            font-size:11px;font-weight:700;text-transform:capitalize;
            border:1px solid #cfe0ff;background:#edf4ff;color:#2e538a;
        }
        .tag--completed,.tag--paid{background:#e8fff4;border-color:#bce9d1;color:#0d6f43}
        .tag--in-progress,.tag--partially-paid,.tag--in-review{background:#eaf4ff;border-color:#c7ddfb;color:#1656a6}
        .tag--pending,.tag--unpaid,.tag--open{background:#fff8e6;border-color:#f2dfac;color:#835d00}
        .tag--delayed,.tag--cancelled,.tag--overdue{background:#ffecec;border-color:#ffd1d1;color:#a52626}
        .table-wrap{overflow:auto;border:1px solid #e3ecfb;border-radius:12px}
        table{width:100%;border-collapse:collapse;min-width:680px}
        th,td{padding:10px;border-bottom:1px solid #edf2fb;text-align:left;vertical-align:top}
        th{background:#f7faff;color:#486388;font-size:12px;text-transform:uppercase;letter-spacing:.04em}
        tr:last-child td{border-bottom:none}
        input,select,textarea{
            width:100%;padding:10px;border:1px solid #ccd9f1;border-radius:11px;
            font-family:inherit;font-size:14px;background:#fff;
        }
        textarea{min-height:108px;resize:vertical}
        .btn{
            border:none;background:linear-gradient(130deg,var(--accent),#1a73c9);
            color:#fff;padding:10px 14px;border-radius:11px;cursor:pointer;font-weight:700;
            display:inline-flex;align-items:center;justify-content:center;min-width:120px;
        }
        .btn:disabled{opacity:.65;cursor:not-allowed}
        .pay-grid{display:grid;grid-template-columns:1fr 1fr 1fr auto;gap:8px;align-items:center}
        .invoice-balance{font-size:12px;color:var(--muted)}
        .invoice-legal-note{
            margin-top:10px;
            padding:10px 12px;
            border:1px solid #dbe7fc;
            border-radius:10px;
            background:#f9fbff;
            font-size:12px;
            line-height:1.5;
            color:#4f688a;
        }
        .invoice-legal-note strong{color:#1b3658}
        .form-grid{display:grid;grid-template-columns:1fr;gap:10px}
        .field-label{font-size:13px;font-weight:700;color:#294b7a;margin:2px 0 -2px}
        .input-note{margin:-4px 0 0;font-size:12px;color:var(--muted)}
        .empty-state{
            padding:14px;border:1px dashed #d2e2fa;border-radius:11px;background:#f9fbff;
            color:#5e738f;font-size:14px;
        }
        .support-card{
            border:1px solid #d8e4fa;border-radius:14px;background:linear-gradient(180deg,#f7faff 0%,#f1f6ff 100%);
            padding:14px;
        }
        .support-card p{margin:0 0 10px;font-size:14px}
        .support-actions{display:flex;flex-wrap:wrap;gap:8px}
        .support-link{
            display:inline-flex;align-items:center;justify-content:center;
            padding:8px 12px;border-radius:999px;border:1px solid #c7dcfb;background:#fff;
            color:#1a4f99;text-decoration:none;font-size:13px;font-weight:700;
        }
        .support-link:hover{background:#edf5ff}
        .quick-guide{
            display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:12px;margin-bottom:16px;
        }
        .quick-guide__item{
            border:1px solid #dbe7fc;border-radius:14px;padding:12px;background:#ffffff;
            box-shadow:0 8px 18px rgba(13,49,106,.04);
        }
        .quick-guide__item b{display:block;color:#1a467f;font-size:14px;margin-bottom:5px}
        .quick-guide__item span{font-size:13px;color:#5d7190}
        @media (max-width: 1080px){
            .kpis{grid-template-columns:repeat(2,minmax(0,1fr))}
            .layout{grid-template-columns:1fr}
            .quick-guide{grid-template-columns:repeat(2,minmax(0,1fr))}
        }
        @media (max-width: 700px){
            .hero h1{font-size:24px}
            .progress-meta{grid-template-columns:repeat(2,minmax(0,1fr))}
            .pay-grid{grid-template-columns:1fr}
            .btn{width:100%}
            .support-actions{flex-direction:column}
            .quick-guide{grid-template-columns:1fr}
        }
    </style>
</head>
<body>
<div class="shell">
    @if(session('success'))<div class="flash ok">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="flash er">{{ $errors->first() }}</div>@endif

    <div class="hero">
        <div class="hero-top">
            <div>
                <h1>{{ $project->title }}</h1>
                <p class="sub">{{ $project->client?->name }} · {{ $project->client?->company ?: 'UK Client Project' }}</p>
                <div class="hero-nav">
                    <a href="#timeline" class="quick-link">Timeline</a>
                    <a href="#milestones" class="quick-link">Milestones</a>
                    <a href="#invoices" class="quick-link">Invoices</a>
                    <a href="#requirements" class="quick-link">Requirements</a>
                </div>
            </div>
            <div class="status-chip">
                <span class="status-dot"></span>
                {{ str_replace('_',' ', ucfirst($project->status)) }}
            </div>
        </div>
    </div>

    <div class="kpis">
        <div class="kpi"><b>Total Budget</b><span>{{ $project->currency }} {{ number_format((float)$project->budget_total,2) }}</span><small>Approved scope budget</small></div>
        <div class="kpi"><b>Total Paid</b><span>{{ $project->currency }} {{ number_format((float)$project->paid_total,2) }}</span><small>Payments received</small></div>
        <div class="kpi"><b>Outstanding</b><span>{{ $project->currency }} {{ number_format((float)$balance,2) }}</span><small>Remaining to pay</small></div>
        <div class="kpi"><b>Delivery Date</b><span style="font-size:22px">{{ optional($project->delivery_date)->format('d M Y') ?: '-' }}</span><small>Current target</small></div>
    </div>

    <div class="quick-guide">
        <div class="quick-guide__item"><b>1. Check Timeline</b><span>See overall progress and expected delivery updates.</span></div>
        <div class="quick-guide__item"><b>2. Review Milestones</b><span>Track what is completed, in progress, and upcoming.</span></div>
        <div class="quick-guide__item"><b>3. Add Requirement</b><span>Submit requested changes with clear details.</span></div>
        <div class="quick-guide__item"><b>4. Manage Invoices</b><span>View balance, payment status, and submit payments.</span></div>
    </div>

    <div class="layout">
        <div>
            <div class="card" id="timeline">
                <h2>Timeline Overview</h2>
                <p class="section-sub">Track your project progress from start date to delivery date.</p>
                @if(!is_null($timeline['progress_percent']))
                    <div class="progress-meta">
                        <div class="mini">Total Duration<strong>{{ $timeline['total_days'] }} days</strong></div>
                        <div class="mini">Elapsed<strong>{{ $timeline['elapsed_days'] }} days</strong></div>
                        <div class="mini">Remaining<strong>{{ $timeline['remaining_days'] }} days</strong></div>
                        <div class="mini">Completion<strong>{{ $timeline['progress_percent'] }}%</strong></div>
                    </div>
                    <div class="progress-wrap"><div class="progress-bar" style="width: {{ $timeline['progress_percent'] }}%"></div></div>
                    <p class="progress-label">{{ $timeline['progress_percent'] >= 100 ? 'Project timeline completed' : 'Project is currently in progress' }}</p>
                @else
                    <p class="empty-state" style="margin:0">Timeline appears once project start and delivery dates are finalized by admin.</p>
                @endif
            </div>

            <div class="card" id="milestones">
                <h3>Milestones</h3>
                <p class="section-sub">Each milestone shows current status and expected due date.</p>
                <ul class="list">
                    @forelse($project->milestones as $milestone)
                        @php($milestoneStatusClass = str_replace('_','-', strtolower($milestone->status)))
                        <li>
                            <div class="list-top">
                                <strong>{{ $milestone->title }}</strong>
                                <span class="tag tag--{{ $milestoneStatusClass }}">{{ str_replace('_',' ', ucfirst($milestone->status)) }}</span>
                            </div>
                            <div class="muted" style="margin-top:6px">{{ $milestone->details ?: 'No details added yet.' }}</div>
                            <div class="muted" style="font-size:13px;margin-top:5px">Due: {{ optional($milestone->due_date)->format('d M Y') ?: 'Not set' }}</div>
                        </li>
                    @empty
                        <li class="empty-state">No milestones defined yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div>
            <div class="card" id="requirements">
                <h3>Requirements</h3>
                <p class="section-sub">You can submit new requests here and monitor their status.</p>
                <div class="table-wrap" style="margin-bottom:12px">
                    <table>
                        <thead><tr><th>Requirement</th><th>Source</th><th>Status</th></tr></thead>
                        <tbody>
                        @forelse($project->requirements as $requirement)
                            @php($requirementStatusClass = str_replace('_','-', strtolower($requirement->status)))
                            <tr>
                                <td><strong>{{ $requirement->title }}</strong><br><span class="muted">{{ $requirement->description ?: '-' }}</span></td>
                                <td>{{ strtoupper($requirement->source) }}</td>
                                <td><span class="tag tag--{{ $requirementStatusClass }}">{{ str_replace('_',' ', ucfirst($requirement->status)) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="muted">No requirements added yet.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <form action="{{ route('client.portal.requirement', $project->portal_token) }}" method="post" class="form-grid">
                    @csrf
                    <label class="field-label" for="requirement-title">Requirement Title</label>
                    <input id="requirement-title" name="title" placeholder="Example: Add testimonials section on homepage" required>
                    <label class="field-label" for="requirement-description">Requirement Details</label>
                    <textarea id="requirement-description" name="description" placeholder="Describe what you need, expected outcome, and any reference links"></textarea>
                    <p class="input-note">Tip: Clear details help our team deliver faster and with fewer revisions.</p>
                    <button type="submit" class="btn">Submit Requirement</button>
                </form>
            </div>

            <div class="support-card">
                <h3 style="margin:0 0 8px;color:var(--primary);font-size:18px;">Need Help?</h3>
                <p>If you cannot find an update or need quick assistance, contact the project team directly.</p>
                <div class="support-actions">
                    <a class="support-link" href="mailto:info@arsdeveloper.co.uk">Email Support</a>
                    <a class="support-link" href="{{ url('/client-portal-access') }}">Portal Access Guide</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card" id="invoices">
        <h3>Invoices & Payments</h3>
        <p class="section-sub">Review invoice balances and pay securely by Stripe card from this section.</p>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Invoice</th><th>Date</th><th>Amount</th><th>Paid</th><th>Status</th><th>Pay</th></tr></thead>
                <tbody>
                @forelse($project->invoices as $invoice)
                    @php($remaining = max(0, (float)$invoice->amount - (float)$invoice->paid_amount))
                    @php($invoiceStatusClass = str_replace('_','-', strtolower($invoice->status)))
                    <tr>
                        <td>
                            <strong>{{ $invoice->invoice_number }}</strong><br>
                            <span class="muted">Client Ref: {{ $invoice->client_invoice_number ?: '-' }}</span>
                        </td>
                        <td>{{ optional($invoice->invoice_date)->format('d M Y') ?: '-' }}<br><span class="muted">Due: {{ optional($invoice->due_date)->format('d M Y') ?: '-' }}</span></td>
                        <td>{{ $project->currency }} {{ number_format((float)$invoice->amount,2) }}</td>
                        <td>{{ $project->currency }} {{ number_format((float)$invoice->paid_amount,2) }}</td>
                        <td>
                            <span class="tag tag--{{ $invoiceStatusClass }}">{{ str_replace('_',' ', ucfirst($invoice->status)) }}</span>
                            <div class="invoice-balance">Balance: {{ $project->currency }} {{ number_format($remaining,2) }}</div>
                        </td>
                        <td>
                            @if($remaining > 0)
                                <form action="{{ route('client.portal.pay', $project->portal_token) }}" method="post" class="pay-grid">
                                    @csrf
                                    <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                                    <input type="number" step="0.01" min="0.01" max="{{ $remaining }}" name="amount" value="{{ $remaining }}" required aria-label="Payment amount">
                                    <select name="method" aria-label="Payment method">
                                        <option value="Portal Payment">Stripe Card</option>
                                        <option value="Card">Card (Stripe)</option>
                                        <option value="Bank Transfer">Bank Transfer</option>
                                    </select>
                                    <input name="reference" placeholder="Reference" aria-label="Payment reference">
                                    <button type="submit" class="btn">Pay Securely</button>
                                </form>
                            @else
                                <button class="btn" disabled>Paid</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="muted">No invoices added yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="invoice-legal-note">
            &copy; {{ now()->year }} {{ config('company.legal_name') }}.<br>
            Company No: {{ config('company.company_number') }} | Registered in {{ config('company.registered_in') }}
        </div>
    </div>
</div>
</body>
</html>
