<!doctype html>
<html lang="en">
<head>
    @php
        $isPrintMode = request()->query('print') === '1';
    @endphp
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex, nofollow">
    <meta name="bingbot" content="noindex, nofollow">
    <link rel="canonical" href="{{ route('invoice.public.show', ['token' => $invoice->public_token]) }}">
    <title>{{ $invoice->invoice_number }} | ARS Developer Invoice</title>
    <style>
        :root{--bg:#ffffff;--paper:#fff;--line:#cfd3dc;--text:#111827;--muted:#4b5563;--brand:#1f63c8}
        *{box-sizing:border-box}
        body{margin:0;font-family:Arial,sans-serif;background:var(--bg);color:var(--text)}
        .wrap{max-width:980px;margin:10px auto;padding:0 12px}
        .paper{background:var(--paper);padding:20px}
        .no-print{display:block}
        .actions{display:flex;gap:8px;flex-wrap:wrap;margin:0 0 10px}
        .btn{display:inline-block;text-decoration:none;border:none;border-radius:8px;padding:10px 14px;font-weight:700;cursor:pointer;font-size:14px}
        .btn-primary{background:var(--brand);color:#fff}
        .btn-light{background:#fff;color:#1f4f96;border:1px solid #bfd0ec}
        .btn:disabled{opacity:.55;cursor:not-allowed}
        .head{display:flex;justify-content:space-between;align-items:flex-start;gap:18px;border-bottom:2px solid #0f172a;padding-bottom:14px;margin-bottom:18px}
        .head-left h1{margin:0;font-size:52px;line-height:1;font-weight:800;letter-spacing:.03em}
        .head-left p{margin:8px 0 0;color:var(--muted);font-size:14px}
        .head-right{font-size:13px;line-height:1.7;text-align:right}
        .head-right b{display:inline-block;min-width:120px}
        .block-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px}
        .block{padding:0 0 4px}
        .block h2{margin:0 0 8px;font-size:15px;text-transform:uppercase;letter-spacing:.04em}
        .block p{margin:0 0 4px;font-size:14px}
        .table{width:100%;border-collapse:collapse;margin:10px 0 14px}
        .table th,.table td{border:1px solid #9ca3af;padding:10px 12px;text-align:left;vertical-align:top}
        .table th{background:#000;color:#fff;font-size:14px;font-weight:700}
        .table td:last-child,.table th:last-child{text-align:right;white-space:nowrap}
        .totals{display:flex;justify-content:flex-end;margin:8px 0 0}
        .totals-box{min-width:320px;padding:4px 0}
        .totals-row{display:flex;justify-content:space-between;gap:10px;margin-bottom:6px;font-size:14px}
        .totals-row.final{font-size:22px;font-weight:800;color:#0f172a;margin-top:8px}
        .pay-box{margin-top:16px;border-top:1px solid #cfd3dc;padding:12px 0 0}
        .pay-box h3{margin:0 0 8px;font-size:16px}
        .field{margin-top:10px}
        .field label{display:block;margin-bottom:6px;color:#374151;font-size:13px}
        .field input{width:100%;padding:10px;border:1px solid #cfd7e6;border-radius:8px;font-size:14px}
        .scope{margin:0;padding-left:18px}
        .scope li{margin-bottom:4px;font-size:14px}
        .note{margin-top:12px;font-size:14px}
        .footer{margin-top:18px;border-top:1px solid #d5dbe6;padding-top:10px;text-align:center;color:#6b7280;font-size:12px}
        .status-pill{display:inline-block;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:700;background:#e7effc;color:#1f4f96}
        .alert{padding:10px 12px;border-radius:8px;margin-bottom:10px}
        .alert.ok{background:#eaf9f1;border:1px solid #bfe6cf;color:#0f6a41}
        .alert.err{background:#fff1f1;border:1px solid #f3cccc;color:#8f2236}
        @media (max-width:800px){
            .head{display:block}
            .head-right{text-align:left;margin-top:10px}
            .head-left h1{font-size:36px}
            .block-grid{grid-template-columns:1fr}
            .totals{justify-content:stretch}
            .totals-box{width:100%;min-width:0}
        }
        @media print{
            body{background:#fff}
            .wrap{max-width:100%;margin:0;padding:0}
            .paper{border:none;padding:8mm}
            .no-print,.actions{display:none !important}
            @page { margin: 8mm; size: A4; }
        }
    </style>
</head>
<body>
<div class="wrap">
    <div class="actions no-print">
        <a href="{{ route('invoice.public.show', ['token' => $invoice->public_token]) }}" class="btn btn-light">Invoice</a>
        <a href="{{ route('invoice.public.show', ['token' => $invoice->public_token]) }}?print=1" class="btn btn-light">Print / PDF</a>
        @if($invoice->show_pay_button && $balance > 0)
            <a href="#payment" class="btn btn-primary">Pay Now</a>
        @endif
    </div>
    <div class="paper">
        <div class="head">
            <div class="head-left">
                <h1>SERVICE INVOICE</h1>
                <p>{{ $invoicePayload['headline'] ?: ('Invoice for ' . ($project->title ?: 'Service')) }}</p>
            </div>
            <div class="head-right">
                <div><b>Invoice Number:</b> {{ $invoice->invoice_number }}</div>
                <div><b>Client Ref:</b> {{ $invoice->client_invoice_number ?: '-' }}</div>
                <div><b>Date:</b> {{ optional($invoice->invoice_date)->format('d M Y') ?: '-' }}</div>
                <div><b>Due Date:</b> {{ optional($invoice->due_date)->format('d M Y') ?: '-' }}</div>
                <div><b>Status:</b> {{ str_replace('_', ' ', ucfirst((string) $invoice->status)) }}</div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert ok">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert err">{{ $errors->first() }}</div>
        @endif

        <div class="block-grid">
            <div class="block">
                <h2>From</h2>
                <p><strong>{{ config('company.legal_name') }}</strong></p>
                <p>{{ config('company.registered_office') }}</p>
                <p>Company No: {{ config('company.company_number') }}</p>
                <p>Email: {{ config('mail.from.address') }}</p>
            </div>
            <div class="block">
                <h2>Bill To</h2>
                <p><strong>{{ $invoicePayload['client_name'] ?: ($project->client?->name ?: '-') }}</strong></p>
                <p>{{ $invoicePayload['client_company'] ?: ($project->client?->company ?: '-') }}</p>
                <p>Email: {{ $invoicePayload['client_email'] ?: ($project->client?->email ?: '-') }}</p>
                <p>Phone: {{ $invoicePayload['client_phone'] ?: ($project->client?->phone ?: '-') }}</p>
            </div>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th>Service</th>
                <th>Details</th>
                <th>Amount ({{ $project->currency }})</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $project->title ?: 'Service Work' }}</td>
                <td>
                    @php
                        $points = collect((array) ($invoicePayload['scope_points'] ?? []))->filter()->take(2)->values();
                    @endphp
                    @if($points->isNotEmpty())
                        <ul class="scope">
                            @foreach($points as $point)
                                <li>{{ $point }}</li>
                            @endforeach
                        </ul>
                    @else
                        {{ $invoicePayload['project_summary'] ?: 'Website work as agreed.' }}
                    @endif
                </td>
                <td>{{ number_format((float) $invoice->amount, 2) }}</td>
            </tr>
            </tbody>
        </table>

        <div class="totals">
            <div class="totals-box">
                <div class="totals-row"><span>Total</span><strong>{{ $project->currency }} {{ number_format((float) $invoice->amount, 2) }}</strong></div>
                <div class="totals-row"><span>Paid</span><strong>{{ $project->currency }} {{ number_format((float) $invoice->paid_amount, 2) }}</strong></div>
                <div class="totals-row final"><span>Outstanding</span><span>{{ $project->currency }} {{ number_format((float) $balance, 2) }}</span></div>
            </div>
        </div>

        @if(!$isPrintMode)
            <div class="pay-box" id="payment">
                <h3>Pay This Invoice</h3>
                @if($invoice->show_pay_button && $balance > 0)
                    <form method="post" action="{{ route('invoice.public.pay', ['token' => $invoice->public_token]) }}">
                        @csrf
                        <div class="field">
                            <label>Payment Amount (optional)</label>
                            <input type="number" name="amount" min="0.01" step="0.01" value="{{ old('amount', $balance > 0 ? number_format((float) $balance, 2, '.', '') : '') }}">
                        </div>
                        <div class="field">
                            <label>Reference (optional)</label>
                            <input type="text" name="reference" maxlength="120" value="{{ old('reference') }}">
                        </div>
                        <div class="field">
                            <button type="submit" class="btn btn-primary">{{ $invoicePayload['payment_label'] ?: 'Pay Now' }}</button>
                        </div>
                    </form>
                @elseif($balance <= 0)
                    <button type="button" class="btn btn-light" disabled>Invoice Already Paid</button>
                @else
                    <button type="button" class="btn btn-light" disabled>Online Payment Disabled</button>
                @endif
            </div>
        @endif

        @if(!empty($invoicePayload['terms']))
            <div class="note"><strong>Terms:</strong> {{ $invoicePayload['terms'] }}</div>
        @endif

        @if(!empty($invoicePayload['extra_notes']))
            <div class="note"><strong>Note:</strong> {{ $invoicePayload['extra_notes'] }}</div>
        @endif

        <div class="footer">
            {{ config('company.legal_name') }} | Company No: {{ config('company.company_number') }} | Registered in {{ config('company.registered_in') }}
        </div>
    </div>
</div>
@if($isPrintMode)
<script>window.addEventListener('load', function(){ window.print(); });</script>
@endif
</body>
</html>
