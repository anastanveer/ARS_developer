@extends('admin.layout', ['title' => 'Invoice Studio'])

@section('content')
<div class="top">
    <h1 style="margin:0">Invoice Studio - {{ $invoice->invoice_number }}</h1>
    <div>
        <a href="{{ route('admin.projects.show', $project) }}" class="btn gray">Back to Project</a>
    </div>
</div>

<div class="card" style="margin-bottom:16px">
    <h3 style="margin-top:0">Public Invoice Link</h3>
    <div class="preview-box">
        <strong>{{ $publicUrl }}</strong>
        <div class="muted">Client can open this link and pay with Stripe (if pay button is enabled).</div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:10px">
            <a href="{{ $publicUrl }}" target="_blank" rel="noopener" class="btn">Open Invoice</a>
            <a href="{{ $publicUrl }}?print=1" target="_blank" rel="noopener" class="btn gray">Print / PDF</a>
            <a href="{{ route('invoice.public.pay-now', ['token' => $invoice->public_token]) }}" target="_blank" rel="noopener" class="btn gray">Open Payment</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="card">
        <h3 style="margin-top:0">Invoice Content</h3>
        <form method="post" action="{{ route('admin.projects.invoices.studio.save', [$project, $invoice]) }}" class="row">
            @csrf

            <div class="full">
                <label style="display:block;margin-bottom:6px;font-weight:700;">Payment Option</label>
                <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                    <input type="checkbox" name="show_pay_button" value="1" @checked($invoice->show_pay_button) style="width:18px;height:18px;accent-color:#1f76d2;">
                    <span>Enable Stripe Pay Now button on public invoice page</span>
                </label>
            </div>

            <div class="full">
                <label>Headline</label>
                <input name="headline" value="{{ old('headline', $payload['headline'] ?? '') }}" maxlength="190">
            </div>

            <div class="full">
                <label>Intro</label>
                <textarea name="intro" maxlength="700">{{ old('intro', $payload['intro'] ?? '') }}</textarea>
            </div>

            <div>
                <label>Client Name</label>
                <input name="client_name" value="{{ old('client_name', $payload['client_name'] ?? '') }}" maxlength="180">
            </div>
            <div>
                <label>Company</label>
                <input name="client_company" value="{{ old('client_company', $payload['client_company'] ?? '') }}" maxlength="180">
            </div>
            <div>
                <label>Client Email</label>
                <input type="email" name="client_email" value="{{ old('client_email', $payload['client_email'] ?? '') }}" maxlength="180">
            </div>
            <div>
                <label>Client Phone</label>
                <input name="client_phone" value="{{ old('client_phone', $payload['client_phone'] ?? '') }}" maxlength="70">
            </div>

            <div class="full">
                <label>Project Summary</label>
                <textarea name="project_summary" maxlength="2200">{{ old('project_summary', $payload['project_summary'] ?? '') }}</textarea>
            </div>

            <div class="full">
                <label>Scope Points (one line per point)</label>
                <textarea name="scope_points" maxlength="3500">{{ old('scope_points', implode(PHP_EOL, (array) ($payload['scope_points'] ?? []))) }}</textarea>
            </div>

            <div class="full">
                <label>Terms</label>
                <textarea name="terms" maxlength="2400">{{ old('terms', $payload['terms'] ?? '') }}</textarea>
            </div>

            <div class="full">
                <label>Extra Notes</label>
                <textarea name="extra_notes" maxlength="2400">{{ old('extra_notes', $payload['extra_notes'] ?? '') }}</textarea>
            </div>

            <div class="full">
                <label>Payment Button Label</label>
                <input name="payment_label" value="{{ old('payment_label', $payload['payment_label'] ?? '') }}" maxlength="90">
            </div>

            <div class="full">
                <button class="btn" type="submit">Save Invoice Studio</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h3 style="margin-top:0">Send Invoice Link</h3>
        <form method="post" action="{{ route('admin.projects.invoices.send-link', [$project, $invoice]) }}" class="row">
            @csrf
            <div class="full">
                <label>Client Email</label>
                <input type="email" name="email" required value="{{ old('email', $project->client?->email) }}">
            </div>
            <div>
                <label>Send Link Type</label>
                <select name="link_mode">
                    <option value="invoice" @selected(old('link_mode')==='invoice')>Invoice + Payment</option>
                    <option value="payment" @selected(old('link_mode')==='payment')>Direct Payment Link</option>
                    <option value="pdf" @selected(old('link_mode')==='pdf')>PDF / Print Link</option>
                </select>
            </div>
            <div class="full">
                <label>Email Subject</label>
                <input name="subject" value="{{ old('subject') }}" placeholder="Leave blank for auto subject">
            </div>
            <div class="full">
                <button class="btn" type="submit">Send Invoice Link by Email</button>
            </div>
        </form>

        @if ($invoice->sent_to_email || $invoice->sent_at)
            <hr style="border:none;border-top:1px solid #e9effb;margin:14px 0">
            <div class="muted">
                Last sent:
                <strong>{{ $invoice->sent_to_email ?: '-' }}</strong>
                @if($invoice->sent_at)
                    on {{ $invoice->sent_at->format('d M Y H:i') }}
                @endif
            </div>
        @endif

        <hr style="border:none;border-top:1px solid #e9effb;margin:14px 0">
        <h4 style="margin:0 0 8px">Quick Preview</h4>
        <div class="preview-box">
            <div><strong>Invoice:</strong> {{ $invoice->invoice_number }}</div>
            <div><strong>Client Ref:</strong> {{ $invoice->client_invoice_number ?: 'N/A' }}</div>
            <div><strong>Amount:</strong> {{ $project->currency }} {{ number_format((float) $invoice->amount, 2) }}</div>
            <div><strong>Paid:</strong> {{ $project->currency }} {{ number_format((float) $invoice->paid_amount, 2) }}</div>
            <div><strong>Balance:</strong> {{ $project->currency }} {{ number_format(max(0, (float)$invoice->amount - (float)$invoice->paid_amount), 2) }}</div>
            <div style="margin-top:10px">
                <a href="{{ $publicUrl }}" target="_blank" rel="noopener" class="btn">Open Public Invoice Page</a>
            </div>
        </div>
    </div>
</div>
@endsection
