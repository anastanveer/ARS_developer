@extends('admin.layout', ['title' => 'Invoices'])

@section('content')
<div class="top">
    <h1 class="page-title">Invoice & Payment Center</h1>
    <div style="display:flex;gap:8px;flex-wrap:wrap">
        <a href="{{ route('admin.dashboard') }}" class="btn gray">Dashboard</a>
        <a href="{{ route('admin.projects.index') }}" class="btn gray">Projects</a>
    </div>
</div>

<div class="card" style="margin-bottom:14px">
    <div class="muted">
        This is your full invoice area in admin. Path: <strong>Dashboard → Invoices</strong>.
    </div>
    <div class="muted" style="margin-top:8px">
        Simple flow: <strong>Create invoice</strong> → <strong>Send link</strong> → <strong>Client pays</strong> →
        <strong>Mark status</strong> (<em>Pending / Received / Failed / Late</em>) →
        <strong>Client gets update email</strong>.
    </div>
</div>

<div class="card" style="margin-bottom:14px">
    <h3 style="margin:0 0 8px">How to Use (Simple)</h3>
    <div class="muted">
        1) Fill invoice/client fields once (invoice number can stay blank, auto-generated).
        2) Click <strong>Create & Send</strong>.
        3) Client gets invoice / payment / PDF / portal link by email.
        4) Update to <strong>Received / Failed / Late</strong> from actions and client gets status email instantly.
    </div>
</div>

<div class="card" style="margin-bottom:14px">
    <h3 style="margin-top:0">Direct Invoice / Payment Link Builder</h3>
    <div class="muted" style="margin-bottom:10px">
        No existing project required. Fill client + invoice details once, then send:
        invoice link, direct payment link, PDF/print link, or portal link in one click.
    </div>
    <form method="post" action="{{ route('admin.invoices.direct-payment-link') }}" class="row3" id="directInvoiceBuilderForm">
        @csrf
        <div>
            <label>Client Name</label>
            <input type="text" name="client_name" required value="{{ old('client_name') }}" placeholder="Client full name">
        </div>
        <div>
            <label>Client Email</label>
            <input type="email" name="client_email" required value="{{ old('client_email') }}" placeholder="client@company.com">
        </div>
        <div>
            <label>Client Company</label>
            <input type="text" name="client_company" value="{{ old('client_company') }}" placeholder="Company name">
        </div>
        <div>
            <label>Client Phone</label>
            <input type="text" name="client_phone" value="{{ old('client_phone') }}" placeholder="+44...">
        </div>
        <div>
            <label>Project Title</label>
            <input type="text" name="project_title" required value="{{ old('project_title') }}" placeholder="Website & CRM Build">
        </div>
        <div>
            <label>Project Type</label>
            <input type="text" name="project_type" maxlength="80" value="{{ old('project_type') }}" placeholder="Business Website / CRM / SEO">
        </div>
        <div>
            <label>Amount</label>
            <input type="number" step="0.01" min="0.01" name="amount" required value="{{ old('amount') }}" placeholder="1200.00">
        </div>
        <div>
            <label>Currency</label>
            <select name="currency">
                @foreach(['GBP','USD','EUR','CAD','PKR','INR'] as $cur)
                    <option value="{{ $cur }}" @selected(old('currency', 'GBP') === $cur)>{{ $cur }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Link Mode</label>
            <select name="link_mode" id="direct_link_mode">
                <option value="payment" @selected(old('link_mode', 'payment') === 'payment')>Direct payment link (skip invoice screen)</option>
                <option value="invoice" @selected(old('link_mode') === 'invoice')>Invoice screen + payment button</option>
                <option value="pdf" @selected(old('link_mode') === 'pdf')>Invoice PDF / print link</option>
                <option value="portal" @selected(old('link_mode') === 'portal')>Client portal access link</option>
            </select>
        </div>
        <div>
            <label>Invoice Date</label>
            <input type="date" name="invoice_date" value="{{ old('invoice_date', now()->toDateString()) }}">
        </div>
        <div>
            <label>Due Date</label>
            <input type="date" name="due_date" value="{{ old('due_date', now()->addDays(7)->toDateString()) }}">
        </div>
        <div>
            <label>Email Subject (optional)</label>
            <input type="text" name="email_subject" id="direct_email_subject" value="{{ old('email_subject') }}" placeholder="Auto subject by mode">
        </div>
        <div class="full">
            <label>Project Summary</label>
            <textarea name="project_summary" style="min-height:80px" placeholder="Short project scope shown on invoice">{{ old('project_summary') }}</textarea>
        </div>
        <div class="full">
            <label>Requirements (one line per requirement)</label>
            <textarea name="requirements" style="min-height:90px" placeholder="Requirement 1&#10;Requirement 2&#10;Requirement 3">{{ old('requirements') }}</textarea>
        </div>
        <div class="full">
            <label>Notes (optional)</label>
            <textarea name="notes" style="min-height:80px" placeholder="Any invoice-specific notes">{{ old('notes') }}</textarea>
        </div>
        <div class="full" style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
            <input type="hidden" name="send_email" value="0">
            <label style="display:flex;align-items:center;gap:8px;cursor:pointer">
                <input type="checkbox" name="send_email" value="1" @checked(old('send_email', '1') === '1')>
                Send selected link to client email now
            </label>
            <span class="muted">Client receives one-click link based on selected mode. You can resend anytime below.</span>
        </div>
        <div class="full">
            <button class="btn" type="submit">Create & Send</button>
        </div>
    </form>
</div>

@if(session('direct_invoice_url') || session('direct_payment_url') || session('direct_pdf_url') || session('direct_portal_url'))
    <div class="card" style="margin-bottom:14px">
        <h3 style="margin-top:0">Direct Links Ready</h3>
        <div class="muted" style="margin-bottom:8px">
            Share any link by WhatsApp/email. Payment and invoice status will stay synced in admin.
        </div>
        @if(session('direct_invoice_url'))
            <div style="display:grid;grid-template-columns:1fr auto;gap:6px;margin-bottom:8px">
                <input type="text" readonly value="{{ session('direct_invoice_url') }}">
                <button type="button" class="btn gray js-copy-link" data-copy="{{ session('direct_invoice_url') }}">Copy Invoice</button>
            </div>
        @endif
        @if(session('direct_payment_url'))
            <div style="display:grid;grid-template-columns:1fr auto;gap:6px;margin-bottom:8px">
                <input type="text" readonly value="{{ session('direct_payment_url') }}">
                <button type="button" class="btn gray js-copy-link" data-copy="{{ session('direct_payment_url') }}">Copy Payment</button>
            </div>
        @endif
        @if(session('direct_pdf_url'))
            <div style="display:grid;grid-template-columns:1fr auto;gap:6px;margin-bottom:8px">
                <input type="text" readonly value="{{ session('direct_pdf_url') }}">
                <button type="button" class="btn gray js-copy-link" data-copy="{{ session('direct_pdf_url') }}">Copy PDF</button>
            </div>
        @endif
        @if(session('direct_portal_url'))
            <div style="display:grid;grid-template-columns:1fr auto;gap:6px;margin-bottom:8px">
                <input type="text" readonly value="{{ session('direct_portal_url') }}">
                <button type="button" class="btn gray js-copy-link" data-copy="{{ session('direct_portal_url') }}">Copy Portal</button>
            </div>
        @endif
    </div>
@endif

<div class="card" style="margin-bottom:14px">
    <h3 style="margin-top:0">Quick Invoice Builder</h3>
    <form
        method="post"
        id="quickInvoiceBuilderForm"
        data-action-template="{{ route('admin.projects.invoices.store', ['project' => '__PROJECT__']) }}"
        class="row3"
    >
        @csrf
        <div>
            <label>Project</label>
            <select name="project_picker" id="project_picker" required>
                <option value="">Select project</option>
                @foreach($projectsForInvoice as $projectOption)
                    <option
                        value="{{ $projectOption->id }}"
                        data-currency="{{ $projectOption->currency ?: 'GBP' }}"
                        data-client="{{ $projectOption->client?->name ?: '-' }}"
                        data-email="{{ $projectOption->client?->email ?: '' }}"
                        data-company="{{ $projectOption->client?->company ?: ($projectOption->client?->email ?: '-') }}"
                    >
                        #{{ $projectOption->id }} - {{ $projectOption->title }} ({{ $projectOption->client?->name ?: 'Client' }})
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Invoice Number</label>
            <input type="text" name="invoice_number" placeholder="Optional (auto generated)">
            <small class="muted">Leave empty for auto invoice number.</small>
        </div>
        <div>
            <label>Amount</label>
            <input type="number" name="amount" min="0.01" step="0.01" required placeholder="800.00">
        </div>
        <div>
            <label>Invoice Date</label>
            <input type="date" name="invoice_date" required value="{{ now()->toDateString() }}">
        </div>
        <div>
            <label>Due Date</label>
            <input type="date" name="due_date" value="{{ now()->addDays(14)->toDateString() }}">
        </div>
        <div>
            <label>Status</label>
            <select name="status" required>
                <option value="unpaid" selected>Unpaid</option>
                <option value="pending">Pending</option>
                <option value="received">Received (mark paid)</option>
                <option value="late">Late (mark overdue)</option>
                <option value="failed">Failed</option>
                <option value="partially_paid">Partially Paid</option>
                <option value="paid">Paid</option>
                <option value="overdue">Overdue</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
        <div>
            <label>Send To Email</label>
            <input type="email" id="quick_send_to_email" name="send_to_email" placeholder="Auto from selected client">
        </div>
        <div>
            <label>Auto Send Link</label>
            <select name="send_link_mode" id="quick_send_link_mode">
                <option value="invoice" selected>Invoice + payment button</option>
                <option value="payment">Direct payment only</option>
                <option value="pdf">PDF / print only</option>
                <option value="portal">Portal link only</option>
                <option value="none">Do not send now</option>
            </select>
        </div>
        <div>
            <label>Email Subject (optional)</label>
            <input type="text" name="send_subject" id="quick_send_subject" placeholder="Auto subject by selected mode">
        </div>
        <div class="full">
            <label>Notes (optional)</label>
            <textarea name="notes" placeholder="Scope / phase / payment terms" style="min-height:90px"></textarea>
        </div>
        <div class="full" id="quickInvoiceProjectHint" style="margin-top:-4px">
            <span class="muted">Select project to auto-fill client email and send invoice/payment link instantly.</span>
        </div>
        <div class="full">
            <button class="btn" type="submit">Create Invoice Now</button>
        </div>
    </form>
</div>

<form method="get" class="row3" style="margin-bottom:12px;">
    <div>
        <label>Search</label>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Invoice no, client, company, project">
    </div>
    <div>
        <label>Status</label>
        <select name="status">
            <option value="">All statuses</option>
            @foreach([
                'unpaid' => 'Unpaid',
                'partially_paid' => 'Partially Paid',
                'pending' => 'Pending',
                'received' => 'Received (Paid)',
                'late' => 'Late (Overdue)',
                'paid' => 'Paid',
                'overdue' => 'Overdue',
                'failed' => 'Failed',
                'cancelled' => 'Cancelled',
            ] as $statusValue => $statusLabel)
                <option value="{{ $statusValue }}" @selected($statusFilter === $statusValue)>{{ $statusLabel }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label>Source</label>
        <select name="source">
            <option value="">All sources</option>
            <option value="website" @selected(($sourceFilterRaw ?? '') === 'website')>Website Direct Order</option>
            <option value="manual" @selected(($sourceFilterRaw ?? '') === 'manual')>Admin Manual</option>
            <option value="project" @selected(($sourceFilterRaw ?? '') === 'project')>Admin Project</option>
        </select>
    </div>
    <div style="display:flex;align-items:end">
        <button class="btn" type="submit">Apply Filters</button>
    </div>
    <div style="display:flex;align-items:end">
        <a href="{{ route('admin.invoices.index') }}" class="btn gray">Clear</a>
    </div>
</form>

<div class="grid" style="margin-bottom:14px">
    <div class="stat"><b>Total Invoices</b><span>{{ number_format($stats['total']) }}</span></div>
    <div class="stat"><b>Pending</b><span>{{ number_format($stats['pending']) }}</span></div>
    <div class="stat"><b>Unpaid</b><span>{{ number_format($stats['unpaid']) }}</span></div>
    <div class="stat"><b>Overdue</b><span>{{ number_format($stats['overdue']) }}</span></div>
    <div class="stat"><b>Paid</b><span>{{ number_format($stats['paid']) }}</span></div>
    <div class="stat"><b>Website Orders</b><span>{{ number_format($stats['website_orders'] ?? 0) }}</span></div>
    <div class="stat"><b>Manual Admin</b><span>{{ number_format($stats['manual_admin'] ?? 0) }}</span></div>
    <div class="stat"><b>Project Invoices</b><span>{{ number_format($stats['project_admin'] ?? 0) }}</span></div>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Client / Project</th>
                <th>Dates</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($invoices as $invoice)
                @php
                    $project = $invoice->project;
                    $client = $project?->client;
                    $balance = max(0, (float) $invoice->amount - (float) $invoice->paid_amount);
                    $sourceRaw = (string) data_get($invoice->invoice_payload, 'source', 'admin_project');
                    $sourceLabel = match ($sourceRaw) {
                        'website_direct_order' => 'Website Direct Order',
                        'admin_manual' => 'Admin Manual',
                        'admin_project' => 'Admin Project',
                        default => ucfirst(str_replace('_', ' ', $sourceRaw ?: 'unknown')),
                    };
                @endphp
                <tr>
                    <td>
                        <strong>{{ $invoice->invoice_number }}</strong><br>
                        <span class="muted">Client Ref: {{ $invoice->client_invoice_number ?: '-' }}</span>
                    </td>
                    <td>
                        <strong>{{ $client?->name ?: '-' }}</strong><br>
                        <span class="muted">{{ $client?->company ?: ($client?->email ?: '-') }}</span><br>
                        <span class="muted">{{ $project?->title ?: '-' }}</span><br>
                        <span class="muted">Source: {{ $sourceLabel }}</span>
                    </td>
                    <td>
                        {{ optional($invoice->invoice_date)->format('d M Y') ?: '-' }}<br>
                        <span class="muted">Due: {{ optional($invoice->due_date)->format('d M Y') ?: '-' }}</span>
                    </td>
                    <td>
                        {{ $project?->currency ?: 'GBP' }} {{ number_format((float) $invoice->amount, 2) }}<br>
                        <span class="muted">Paid: {{ number_format((float) $invoice->paid_amount, 2) }}</span><br>
                        <span class="muted">Balance: {{ number_format($balance, 2) }}</span>
                    </td>
                    <td>
                        @php
                            $statusLabel = match ((string) $invoice->status) {
                                'paid' => 'Payment Received',
                                'pending' => 'Payment Pending',
                                'failed' => 'Payment Failed',
                                'overdue' => 'Payment Late',
                                'partially_paid' => 'Partially Paid',
                                'cancelled' => 'Cancelled',
                                default => 'Unpaid',
                            };
                        @endphp
                        <span class="pill">{{ str_replace('_', ' ', ucfirst($invoice->status)) }}</span>
                        <div class="muted" style="margin-top:4px;font-size:12px">{{ $statusLabel }}</div>
                        @if($invoice->sent_at)
                            <div class="muted" style="margin-top:6px;font-size:12px">
                                Sent {{ $invoice->sent_at->diffForHumans() }}
                            </div>
                        @endif
                    </td>
                    <td>
                        @if($project)
                            <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:8px">
                                <a href="{{ route('admin.projects.show', $project) }}" class="btn gray" style="padding:6px 10px;font-size:12px">Project</a>
                                <a href="{{ route('admin.projects.invoices.studio', [$project, $invoice]) }}" class="btn gray" style="padding:6px 10px;font-size:12px">Studio</a>
                                @if(!empty($invoice->public_token))
                                    <a href="{{ route('invoice.public.show', ['token' => $invoice->public_token]) }}" target="_blank" rel="noopener" class="btn" style="padding:6px 10px;font-size:12px">Open Invoice</a>
                                    <a href="{{ route('invoice.public.pay-now', ['token' => $invoice->public_token]) }}" target="_blank" rel="noopener" class="btn gray" style="padding:6px 10px;font-size:12px">Payment Link</a>
                                @endif
                            </div>

                            @if(!empty($client?->email))
                                <div class="muted" style="font-size:12px;margin-bottom:6px">Send link to: {{ $client->email }}</div>
                                <form method="post" action="{{ route('admin.projects.invoices.send-link', [$project, $invoice]) }}" style="margin-bottom:8px">
                                    @csrf
                                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px">
                                        <input type="email" name="email" value="{{ $client->email }}" placeholder="Client email" required>
                                        <select name="link_mode">
                                            <option value="invoice">Invoice + payment</option>
                                            <option value="payment">Direct payment</option>
                                            <option value="pdf">PDF / print link</option>
                                            <option value="portal">Client portal</option>
                                        </select>
                                        <button class="btn" type="submit" style="padding:6px 10px;font-size:12px;grid-column:1 / -1">Send Email</button>
                                    </div>
                                </form>
                            @endif

                            <form method="post" action="{{ route('admin.projects.invoices.status', [$project, $invoice]) }}" style="margin-bottom:8px">
                                @csrf
                                <input type="hidden" name="send_email" value="1">
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px">
                                    <select name="status" style="min-width:130px">
                                        <option value="unpaid" @selected($invoice->status==='unpaid')>Unpaid</option>
                                        <option value="pending" @selected($invoice->status==='pending')>Pending</option>
                                        <option value="received" @selected($invoice->status==='paid')>Received (mark paid)</option>
                                        <option value="late" @selected($invoice->status==='overdue')>Late (mark overdue)</option>
                                        <option value="failed" @selected($invoice->status==='failed')>Failed</option>
                                        <option value="partially_paid" @selected($invoice->status==='partially_paid')>Partially Paid</option>
                                        <option value="paid" @selected($invoice->status==='paid')>Paid</option>
                                        <option value="overdue" @selected($invoice->status==='overdue')>Overdue</option>
                                        <option value="cancelled" @selected($invoice->status==='cancelled')>Cancelled</option>
                                    </select>
                                    <span class="muted" style="display:flex;align-items:center;font-size:12px">Client email notification enabled</span>
                                    <input type="text" name="status_note" placeholder="Optional note for status email" style="grid-column:1 / -1">
                                    <button class="btn" type="submit" style="padding:6px 10px;font-size:12px;grid-column:1 / -1">Update Status</button>
                                </div>
                            </form>

                            <form method="post" action="{{ route('admin.projects.payments.store', $project) }}">
                                @csrf
                                <input type="hidden" name="invoice_id" value="{{ $invoice->id }}">
                                <input type="hidden" name="payment_date" value="{{ now()->toDateString() }}">
                                <input type="hidden" name="method" value="Manual Admin Entry">
                                <input type="hidden" name="notes" value="Manual payment logged from Invoice Center">
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px">
                                    <input type="number" name="amount" step="0.01" min="0.01" max="{{ number_format($balance, 2, '.', '') }}" value="{{ number_format($balance, 2, '.', '') }}" placeholder="Amount">
                                    <input type="text" name="reference" placeholder="Payment reference (optional)">
                                    <button class="btn alt" type="submit" style="padding:6px 10px;font-size:12px;grid-column:1 / -1">Log Payment + Send Receipt</button>
                                </div>
                            </form>
                        @else
                            <span class="muted">Project record missing for this invoice.</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">No invoices found.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:12px">
        {{ $invoices->links() }}
    </div>
</div>
@endsection

@push('admin_scripts')
<script>
    (function () {
        var form = document.getElementById('quickInvoiceBuilderForm');
        if (!form) return;

        var projectSelect = document.getElementById('project_picker');
        var hint = document.getElementById('quickInvoiceProjectHint');
        var sendToEmail = document.getElementById('quick_send_to_email');
        var sendMode = document.getElementById('quick_send_link_mode');
        var sendSubject = document.getElementById('quick_send_subject');
        var directMode = document.getElementById('direct_link_mode');
        var directSubject = document.getElementById('direct_email_subject');
        var template = form.getAttribute('data-action-template') || '';

        function renderHint() {
            if (!projectSelect || !hint) return;
            var opt = projectSelect.options[projectSelect.selectedIndex];
            if (!opt || !opt.value) {
                hint.innerHTML = '<span class="muted">Select project to auto-fill client email and send invoice/payment link instantly.</span>';
                if (sendToEmail) sendToEmail.value = '';
                return;
            }
            var client = opt.getAttribute('data-client') || '-';
            var company = opt.getAttribute('data-company') || '-';
            var currency = opt.getAttribute('data-currency') || 'GBP';
            var email = opt.getAttribute('data-email') || '';
            if (sendToEmail) {
                sendToEmail.value = email;
            }
            hint.innerHTML = '<span class="muted">Client: <strong>' + client + '</strong> · ' +
                'Company: <strong>' + company + '</strong> · Email: <strong>' + (email || '-') + '</strong> · Currency: <strong>' + currency + '</strong></span>';
        }

        if (projectSelect) {
            projectSelect.addEventListener('change', renderHint);
            renderHint();
        }

        function modeSubject(mode) {
            if (mode === 'payment') return 'Payment Link';
            if (mode === 'pdf') return 'Invoice PDF';
            if (mode === 'portal') return 'Client Portal Access';
            if (mode === 'none') return '';
            return 'Invoice Link';
        }

        if (sendMode && sendSubject) {
            sendMode.addEventListener('change', function () {
                if ((sendSubject.value || '').trim() !== '') return;
                sendSubject.placeholder = modeSubject(sendMode.value) + ' (optional custom subject)';
            });
            sendSubject.placeholder = modeSubject(sendMode.value || 'invoice') + ' (optional custom subject)';
        }
        if (directMode && directSubject) {
            directMode.addEventListener('change', function () {
                if ((directSubject.value || '').trim() !== '') return;
                directSubject.placeholder = modeSubject(directMode.value) + ' (optional custom subject)';
            });
            directSubject.placeholder = modeSubject(directMode.value || 'payment') + ' (optional custom subject)';
        }

        form.addEventListener('submit', function (event) {
            var projectId = projectSelect ? String(projectSelect.value || '').trim() : '';
            if (!projectId) {
                event.preventDefault();
                alert('Please select a project before creating invoice.');
                return;
            }
            form.action = template.replace('__PROJECT__', encodeURIComponent(projectId));
        });

        function copyText(text) {
            if (navigator.clipboard && window.isSecureContext) {
                return navigator.clipboard.writeText(text);
            }
            return new Promise(function (resolve, reject) {
                var helper = document.createElement('textarea');
                helper.value = text;
                helper.style.position = 'fixed';
                helper.style.left = '-9999px';
                document.body.appendChild(helper);
                helper.focus();
                helper.select();
                try {
                    var ok = document.execCommand('copy');
                    document.body.removeChild(helper);
                    ok ? resolve() : reject(new Error('Copy failed'));
                } catch (err) {
                    document.body.removeChild(helper);
                    reject(err);
                }
            });
        }

        document.querySelectorAll('.js-copy-link').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var text = btn.getAttribute('data-copy') || '';
                if (!text) return;
                copyText(text).then(function () {
                    var previous = btn.textContent;
                    btn.textContent = 'Copied';
                    setTimeout(function () { btn.textContent = previous; }, 1400);
                });
            });
        });
    })();
</script>
@endpush
