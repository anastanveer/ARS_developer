@extends('admin.layout', ['title' => 'Project Detail'])

@section('content')
<div class="top">
    <h1 style="margin:0">Project #{{ $project->id }} - {{ $project->title }}</h1>
    <div>
        <a href="{{ route('admin.projects.edit', $project) }}" class="btn gray">Edit</a>
        <a href="{{ route('admin.projects.index') }}" class="btn">All Projects</a>
    </div>
</div>

<div class="grid" style="margin-bottom:16px">
    <div class="stat"><b>Client</b><span style="font-size:18px">{{ $project->client?->name }}</span></div>
    <div class="stat"><b>Status</b><span style="font-size:18px">{{ str_replace('_',' ', ucfirst($project->status)) }}</span></div>
    <div class="stat"><b>Total Budget</b><span>{{ $project->currency }} {{ number_format((float)$project->budget_total,2) }}</span></div>
    <div class="stat"><b>Paid</b><span>{{ $project->currency }} {{ number_format((float)$project->paid_total,2) }}</span></div>
    <div class="stat"><b>Balance</b><span>{{ $project->currency }} {{ number_format((float)$balance,2) }}</span></div>
    <div class="stat"><b>Delivery</b><span style="font-size:18px">{{ optional($project->delivery_date)->format('d M Y') ?: '-' }}</span></div>
</div>

<div class="card" style="margin-bottom:16px">
    <h3 style="margin-top:0">Client Portal Link</h3>
    <div class="preview-box">
        <strong>{{ route('client.portal', $project->portal_token) }}</strong>
        <div class="muted">Share this secure URL with client to view timeline, invoices and submit new requirements.</div>
    </div>
</div>

<div class="card" style="margin-bottom:16px">
    <h3 style="margin-top:0">Client Activity Log</h3>
    <div class="muted" style="margin-bottom:10px">All client-origin actions for this specific project appear here.</div>
    @php
        $projectActivity = collect();
        foreach ($project->requirements->where('source', 'client') as $req) {
            $projectActivity->push([
                'at' => $req->created_at,
                'type' => 'Requirement',
                'detail' => $req->title,
                'meta' => strtoupper((string) $req->status),
            ]);
        }
        foreach ($project->payments as $payment) {
            $isClientPortalPayment = str_contains((string) $payment->notes, 'Paid by client via portal.')
                || str_contains((string) $payment->notes, 'Paid by client via Stripe Checkout.')
                || in_array((string) $payment->method, ['Portal Payment', 'Stripe Card'], true);
            if ($isClientPortalPayment) {
                $projectActivity->push([
                    'at' => $payment->created_at,
                    'type' => 'Payment',
                    'detail' => $payment->invoice?->invoice_number ?: 'Payment record',
                    'meta' => $project->currency . ' ' . number_format((float) $payment->amount, 2),
                ]);
            }
        }
        $projectActivity = $projectActivity->sortByDesc('at')->values();
    @endphp
    <table>
        <thead><tr><th>When</th><th>Type</th><th>Detail</th><th>Meta</th></tr></thead>
        <tbody>
        @forelse($projectActivity as $item)
            <tr>
                <td>{{ optional($item['at'])->format('d M Y H:i') }}</td>
                <td>{{ $item['type'] }}</td>
                <td>{{ $item['detail'] }}</td>
                <td>{{ $item['meta'] }}</td>
            </tr>
        @empty
            <tr><td colspan="4">No client activity yet on this project.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="row" style="margin-bottom:16px">
    <div class="card">
        <h3 style="margin-top:0">Milestones</h3>
        <form method="post" action="{{ route('admin.projects.milestones.store', $project) }}" class="row">
            @csrf
            <div><label>Title</label><input name="title" required></div>
            <div><label>Due Date</label><input type="date" name="due_date"></div>
            <div><label>Status</label><select name="status"><option value="pending">Pending</option><option value="in_progress">In Progress</option><option value="done">Done</option></select></div>
            <div><label>Sort Order</label><input type="number" min="0" name="sort_order"></div>
            <div class="full"><label>Details</label><textarea name="details"></textarea></div>
            <div class="full"><button class="btn" type="submit">Add Milestone</button></div>
        </form>
        <table>
            <thead><tr><th>Milestone</th><th>Due</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            @forelse($project->milestones as $milestone)
                <tr>
                    <td><strong>{{ $milestone->title }}</strong><br><span class="muted">{{ $milestone->details }}</span></td>
                    <td>{{ optional($milestone->due_date)->format('d M Y') ?: '-' }}</td>
                    <td>{{ str_replace('_',' ', ucfirst($milestone->status)) }}</td>
                    <td>
                        <form method="post" action="{{ route('admin.projects.milestones.status', [$project, $milestone]) }}" class="inline">
                            @csrf
                            <select name="status" onchange="this.form.submit()">
                                @foreach(['pending','in_progress','done'] as $status)
                                    <option value="{{ $status }}" @selected($milestone->status===$status)>{{ str_replace('_',' ', ucfirst($status)) }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No milestones yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Requirements</h3>
        <form method="post" action="{{ route('admin.projects.requirements.store', $project) }}" class="row">
            @csrf
            <div><label>Title</label><input name="title" required></div>
            <div><label>Source</label><select name="source"><option value="admin">Admin</option><option value="client">Client</option></select></div>
            <div><label>Status</label><select name="status"><option value="open">Open</option><option value="in_progress">In Progress</option><option value="done">Done</option><option value="blocked">Blocked</option></select></div>
            <div class="full"><label>Description</label><textarea name="description"></textarea></div>
            <div class="full"><button class="btn" type="submit">Add Requirement</button></div>
        </form>
        <table>
            <thead><tr><th>Requirement</th><th>Source</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            @forelse($project->requirements as $req)
                <tr>
                    <td><strong>{{ $req->title }}</strong><br><span class="muted">{{ $req->description }}</span></td>
                    <td>{{ strtoupper($req->source) }}</td>
                    <td>{{ str_replace('_',' ', ucfirst($req->status)) }}</td>
                    <td>
                        <form method="post" action="{{ route('admin.projects.requirements.status', [$project, $req]) }}" class="inline">
                            @csrf
                            <select name="status" onchange="this.form.submit()">
                                @foreach(['open','in_progress','done','blocked'] as $status)
                                    <option value="{{ $status }}" @selected($req->status===$status)>{{ str_replace('_',' ', ucfirst($status)) }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">No requirements yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="card">
        <div class="top" style="margin-bottom:8px">
            <h3 style="margin:0">Invoices</h3>
            <a href="{{ route('admin.invoices.index') }}" class="btn gray">Open Invoice Center</a>
        </div>
        <div class="muted" style="margin-bottom:10px">
            For each invoice you can open public link, send direct payment link, send client email, and download PDF/print copy.
        </div>
        @if(session('direct_invoice_url') || session('direct_payment_url') || session('direct_pdf_url') || session('direct_portal_url'))
            <div class="card" style="margin-bottom:10px;background:#f8fbff;border:1px solid #d9e6ff">
                <div class="muted" style="margin-bottom:8px">Latest generated links (ready to share):</div>
                <div style="display:flex;gap:8px;flex-wrap:wrap">
                    @if(session('direct_invoice_url'))
                        <a href="{{ session('direct_invoice_url') }}" class="btn gray" target="_blank" rel="noopener">Open Invoice</a>
                    @endif
                    @if(session('direct_payment_url'))
                        <a href="{{ session('direct_payment_url') }}" class="btn" target="_blank" rel="noopener">Open Payment Link</a>
                    @endif
                    @if(session('direct_pdf_url'))
                        <a href="{{ session('direct_pdf_url') }}" class="btn gray" target="_blank" rel="noopener">Open PDF / Print</a>
                    @endif
                    @if(session('direct_portal_url'))
                        <a href="{{ session('direct_portal_url') }}" class="btn gray" target="_blank" rel="noopener">Open Client Portal</a>
                    @endif
                </div>
            </div>
        @endif
        <form method="post" action="{{ route('admin.projects.invoices.store', $project) }}" class="row">
            @csrf
            <div>
                <label>Invoice #</label>
                <input name="invoice_number" placeholder="Optional (auto generated)">
            </div>
            <div><label>Client Invoice #</label><input value="Auto generated per client" disabled></div>
            <div><label>Invoice Date</label><input type="date" name="invoice_date" required></div>
            <div><label>Due Date</label><input type="date" name="due_date"></div>
            <div><label>Amount</label><input type="number" step="0.01" name="amount" required></div>
            <div><label>Status</label><select name="status"><option value="unpaid">Unpaid</option><option value="pending">Pending</option><option value="received">Received (mark paid)</option><option value="late">Late (mark overdue)</option><option value="failed">Failed</option><option value="partially_paid">Partially Paid</option><option value="paid">Paid</option><option value="overdue">Overdue</option><option value="cancelled">Cancelled</option></select></div>
            <div><label>Send To Email</label><input type="email" name="send_to_email" value="{{ $project->client?->email }}" placeholder="Client email"></div>
            <div><label>Auto Send Link</label><select name="send_link_mode"><option value="invoice">Invoice + payment</option><option value="payment">Direct payment only</option><option value="pdf">PDF / print only</option><option value="portal">Portal only</option><option value="none">Do not send now</option></select></div>
            <div><label>Email Subject (optional)</label><input name="send_subject" placeholder="Auto subject by selected mode"></div>
            <div class="full"><label>Notes</label><textarea name="notes"></textarea></div>
            <div class="full"><button class="btn" type="submit">Create Invoice + Optional Send</button></div>
        </form>
        <table>
            <thead><tr><th>Invoice</th><th>Dates</th><th>Amount</th><th>Paid</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($project->invoices as $invoice)
                <tr>
                    <td>
                        <strong>{{ $invoice->invoice_number }}</strong><br>
                        <span class="muted">Client Ref: {{ $invoice->client_invoice_number ?: 'Not generated' }}</span><br>
                        <span class="muted">{{ $invoice->notes }}</span>
                    </td>
                    <td>{{ optional($invoice->invoice_date)->format('d M Y') }}<br>{{ optional($invoice->due_date)->format('d M Y') ?: '-' }}</td>
                    <td>{{ number_format((float)$invoice->amount,2) }}</td>
                    <td>{{ number_format((float)$invoice->paid_amount,2) }}</td>
                    <td>{{ str_replace('_',' ', ucfirst($invoice->status)) }}</td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:8px">
                            <a href="{{ route('admin.projects.invoices.studio', [$project, $invoice]) }}" class="btn gray" style="padding:6px 10px;font-size:12px">Invoice Studio</a>
                            @if(!empty($invoice->public_token))
                                <a href="{{ route('invoice.public.show', ['token' => $invoice->public_token]) }}" target="_blank" rel="noopener" class="btn" style="padding:6px 10px;font-size:12px">Open Invoice</a>
                                <a href="{{ route('invoice.public.pay-now', ['token' => $invoice->public_token]) }}" target="_blank" rel="noopener" class="btn gray" style="padding:6px 10px;font-size:12px">Payment Link</a>
                            @endif
                        </div>
                        @if(!empty($project->client?->email))
                            <div class="muted" style="font-size:12px;margin-bottom:6px">
                                Send link to: {{ $project->client->email }}
                            </div>
                        @endif
                        @if(!empty($project->client?->email))
                            <form method="post" action="{{ route('admin.projects.invoices.send-link', [$project, $invoice]) }}" style="margin-bottom:8px">
                                @csrf
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px">
                                    <input type="email" name="email" value="{{ $project->client->email }}" placeholder="Client email" required>
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
                        <form method="post" action="{{ route('admin.projects.invoices.status', [$project, $invoice]) }}">
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
                                <input type="text" name="status_note" placeholder="Optional note for email" style="grid-column:1 / -1">
                                <button class="btn" type="submit" style="padding:6px 10px;font-size:12px;grid-column:1 / -1">Update Status</button>
                            </div>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">No invoices yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Payments</h3>
        <form method="post" action="{{ route('admin.projects.payments.store', $project) }}" class="row">
            @csrf
            <div><label>Invoice (optional)</label>
                <select name="invoice_id">
                    <option value="">No invoice link</option>
                    @foreach($project->invoices as $invoice)
                        <option value="{{ $invoice->id }}">{{ $invoice->invoice_number }}</option>
                    @endforeach
                </select>
            </div>
            <div><label>Amount</label><input type="number" step="0.01" name="amount" required></div>
            <div><label>Date</label><input type="date" name="payment_date" required></div>
            <div><label>Method</label><input name="method" placeholder="Bank transfer"></div>
            <div><label>Reference</label><input name="reference"></div>
            <div class="full"><label>Notes</label><textarea name="notes"></textarea></div>
            <div class="full"><button class="btn" type="submit">Log Payment</button></div>
        </form>
        <table>
            <thead><tr><th>ID</th><th>Date</th><th>Amount</th><th>Method</th><th>Payment ID</th><th>Invoice</th></tr></thead>
            <tbody>
            @forelse($project->payments as $payment)
                <tr>
                    <td>#{{ $payment->id }}</td>
                    <td>{{ optional($payment->payment_date)->format('d M Y') }}</td>
                    <td>{{ $project->currency }} {{ number_format((float)$payment->amount,2) }}</td>
                    <td>{{ $payment->method ?: '-' }}</td>
                    <td>
                        Ref: {{ $payment->reference ?: '-' }}<br>
                        Gateway: {{ $payment->gateway_payment_id ?: '-' }}
                    </td>
                    <td>
                        {{ $payment->invoice?->invoice_number ?: '-' }}<br>
                        <span class="muted">{{ $payment->invoice?->client_invoice_number ?: '-' }}</span>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">No payments yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
