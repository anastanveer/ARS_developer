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
    @include('partials.ga4-tracking')
    <title>{{ $invoice->invoice_number }} | ARS Developer Invoice</title>
    <style>
    /* Invoice styling. Deliberately self-contained: this page is printed and saved
       as PDF by clients, so it cannot depend on the site stylesheet loading. */
    :root{
      --ink:#0f172a; --ink-2:#334155; --muted:#64748b; --faint:#94a3b8;
      --line:#e2e8f0; --line-soft:#f1f5f9; --paper:#ffffff; --bg:#f1f5f9;
      --brand:#1f63c8; --brand-dark:#174d9e; --brand-tint:#eef4fd;
      --ok:#047857; --ok-tint:#ecfdf5; --warn:#b45309; --warn-tint:#fffbeb;
    }
    *{box-sizing:border-box}
    body{
      margin:0; background:var(--bg); color:var(--ink);
      font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Inter,Roboto,"Helvetica Neue",Arial,sans-serif;
      font-size:14px; line-height:1.6; -webkit-font-smoothing:antialiased;
    }
    .wrap{max-width:860px;margin:0 auto;padding:28px 18px 56px}
    .actions{display:flex;gap:10px;justify-content:flex-end;margin-bottom:18px;flex-wrap:wrap}
    .btn{display:inline-flex;align-items:center;gap:8px;padding:10px 18px;border-radius:10px;
         font-size:13px;font-weight:600;text-decoration:none;border:1px solid transparent;cursor:pointer;transition:.15s}
    .btn-light{background:var(--paper);color:var(--ink-2);border-color:var(--line)}
    .btn-light:hover{border-color:var(--faint);color:var(--ink)}
    .btn-primary{background:var(--brand);color:#fff;box-shadow:0 1px 2px rgba(15,23,42,.12)}
    .btn-primary:hover{background:var(--brand-dark)}
    .btn:disabled{opacity:.55;cursor:not-allowed}

    .paper{background:var(--paper);border:1px solid var(--line);border-radius:16px;
           box-shadow:0 1px 3px rgba(15,23,42,.06),0 12px 32px rgba(15,23,42,.06);overflow:hidden}

    /* Letterhead */
    .head{padding:34px 38px 28px;border-bottom:1px solid var(--line);
          display:flex;justify-content:space-between;gap:28px;flex-wrap:wrap}
    .brandmark{display:flex;align-items:center;gap:12px;margin-bottom:16px}
    .brandmark .mark{width:38px;height:38px;border-radius:10px;background:var(--brand);color:#fff;
                     display:grid;place-items:center;font-weight:700;font-size:15px;letter-spacing:.5px}
    .brandmark .who{font-weight:700;font-size:15px;letter-spacing:-.1px}
    .brandmark .who span{display:block;font-weight:500;font-size:11.5px;color:var(--muted);letter-spacing:.3px}
    .head h1{margin:0;font-size:11px;font-weight:700;letter-spacing:1.6px;text-transform:uppercase;color:var(--brand)}
    .head .headline{margin:8px 0 0;font-size:20px;font-weight:600;color:var(--ink);letter-spacing:-.2px;max-width:38ch}
    .meta{min-width:230px}
    .meta-row{display:flex;justify-content:space-between;gap:20px;padding:5px 0;font-size:13px}
    .meta-row span{color:var(--muted)}
    .meta-row b{font-weight:600;color:var(--ink)}
    .badge{display:inline-block;padding:4px 11px;border-radius:999px;font-size:11px;font-weight:700;
           letter-spacing:.4px;text-transform:uppercase}
    .badge.paid{background:var(--ok-tint);color:var(--ok)}
    .badge.due{background:var(--warn-tint);color:var(--warn)}
    .badge.other{background:var(--line-soft);color:var(--ink-2)}

    .body{padding:30px 38px 38px}
    .block-grid{display:grid;grid-template-columns:1fr 1fr;gap:26px;margin-bottom:30px}
    .block h2{margin:0 0 10px;font-size:10.5px;font-weight:700;letter-spacing:1.2px;
              text-transform:uppercase;color:var(--faint)}
    .block p{margin:0 0 3px;font-size:13.5px;color:var(--ink-2)}
    .block p strong{color:var(--ink);font-size:14.5px;font-weight:600}

    table.table{width:100%;border-collapse:collapse;margin:0 0 26px}
    table.table th{text-align:left;font-size:10.5px;font-weight:700;letter-spacing:1.1px;text-transform:uppercase;
                   color:var(--faint);padding:0 12px 10px;border-bottom:1px solid var(--line)}
    table.table th:last-child,table.table td:last-child{text-align:right}
    table.table td{padding:16px 12px;border-bottom:1px solid var(--line-soft);vertical-align:top;font-size:13.5px;color:var(--ink-2)}
    table.table td:first-child{font-weight:600;color:var(--ink)}
    ul.scope{margin:0;padding-left:17px}
    ul.scope li{margin:0 0 3px}

    .totals{display:flex;justify-content:flex-end;margin-bottom:30px}
    .totals-box{min-width:290px}
    .totals-row{display:flex;justify-content:space-between;gap:26px;padding:9px 0;font-size:13.5px;color:var(--muted)}
    .totals-row strong{color:var(--ink);font-weight:600}
    .totals-row.final{margin-top:8px;padding:15px 16px;background:var(--brand-tint);border-radius:11px;
                      font-size:16px;font-weight:700;color:var(--brand-dark)}
    .totals-row.final span:last-child{font-variant-numeric:tabular-nums}

    .pay-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:26px}
    .pay-box{border:1px solid var(--line);border-radius:13px;padding:22px}
    .pay-box.card{background:var(--brand-tint);border-color:#d5e3f8}
    .pay-box h3{margin:0 0 5px;font-size:14.5px;font-weight:700;color:var(--ink)}
    .pay-box .hint{margin:0 0 15px;font-size:12.5px;color:var(--muted)}
    .bank-row{display:flex;justify-content:space-between;gap:16px;padding:7px 0;border-bottom:1px dashed var(--line);font-size:13px}
    .bank-row:last-child{border-bottom:0}
    .bank-row span{color:var(--muted)}
    .bank-row b{font-weight:600;color:var(--ink);font-variant-numeric:tabular-nums;text-align:right}
    .field{margin-bottom:12px}
    .field label{display:block;font-size:12px;font-weight:600;color:var(--ink-2);margin-bottom:5px}
    .field input{width:100%;padding:10px 12px;border:1px solid var(--line);border-radius:9px;font-size:14px;
                 font-family:inherit;color:var(--ink);background:#fff}
    .field input:focus{outline:none;border-color:var(--brand);box-shadow:0 0 0 3px rgba(31,99,200,.12)}

    .alert{padding:12px 16px;border-radius:10px;margin-bottom:18px;font-size:13.5px}
    .alert.ok{background:var(--ok-tint);color:var(--ok);border:1px solid #a7f3d0}
    .alert.err{background:#fef2f2;color:#b91c1c;border:1px solid #fecaca}
    .note{padding:14px 16px;background:var(--line-soft);border-radius:10px;margin-bottom:12px;font-size:13px;color:var(--ink-2)}
    .note strong{color:var(--ink)}

    .footer{padding:20px 38px;border-top:1px solid var(--line);background:#fafbfc;
            font-size:11.5px;color:var(--muted);text-align:center;line-height:1.7}

    @media (max-width:640px){
      .wrap{padding:16px 12px 40px}
      .head,.body{padding-left:22px;padding-right:22px}
      .block-grid,.pay-grid{grid-template-columns:1fr;gap:20px}
      .totals-box{min-width:100%}
    }
    /* Printed and saved as PDF far more often than it is read on screen. */
    @media print{
      body{background:#fff}
      .wrap{max-width:100%;padding:0}
      .no-print{display:none !important}
      .paper{border:0;border-radius:0;box-shadow:none}
      .head,.body{padding-left:0;padding-right:0}
      .footer{padding-left:0;padding-right:0;background:transparent}
      .pay-box.card{background:transparent}
      .totals-row.final{background:transparent;border:1px solid var(--line)}
      a[href]:after{content:""}
    }
    </style>
</head>
<body>
@php
    // Show only the account matching this invoice's currency — see config/company.php.
    $currency = strtoupper((string) ($project->currency ?: 'GBP'));
    $bank     = (array) (config('company.bank.' . $currency) ?? []);
    $bankFields = array_filter((array) ($bank['fields'] ?? []));
    $hasBank  = filled($bank['account_name'] ?? null) && count($bankFields) > 0;
    $interac  = $currency === 'CAD' ? (string) config('company.interac_email', '') : '';
    $status  = strtolower((string) $invoice->status);
    $badge   = $balance <= 0 ? 'paid' : (in_array($status, ['overdue','unpaid','sent'], true) ? 'due' : 'other');
    $initials = strtoupper(substr((string) config('company.brand_name', 'ARS'), 0, 3));
@endphp
<div class="wrap">
    <div class="actions no-print">
        <a href="{{ route('invoice.public.show', ['token' => $invoice->public_token]) }}" class="btn btn-light">Invoice</a>
        <a href="{{ route('invoice.public.show', ['token' => $invoice->public_token]) }}?print=1" class="btn btn-light">Print / Save PDF</a>
        @if($invoice->show_pay_button && $balance > 0)
            <a href="#payment" class="btn btn-primary">Pay Now</a>
        @endif
    </div>

    <div class="paper">
        <div class="head">
            <div>
                <div class="brandmark">
                    <div class="mark">{{ $initials }}</div>
                    <div class="who">{{ config('company.legal_name') }}
                        <span>{{ config('company.address_locality') }}, {{ config('company.country_name') }}</span>
                    </div>
                </div>
                <h1>Invoice</h1>
                <p class="headline">{{ $invoicePayload['headline'] ?: ('Invoice for ' . ($project->title ?: 'Service')) }}</p>
            </div>
            <div class="meta">
                <div class="meta-row"><span>Invoice No.</span><b>{{ $invoice->invoice_number }}</b></div>
                @if($invoice->client_invoice_number)
                    <div class="meta-row"><span>Your Reference</span><b>{{ $invoice->client_invoice_number }}</b></div>
                @endif
                <div class="meta-row"><span>Issued</span><b>{{ optional($invoice->invoice_date)->format('d M Y') ?: '—' }}</b></div>
                <div class="meta-row"><span>Due</span><b>{{ optional($invoice->due_date)->format('d M Y') ?: '—' }}</b></div>
                <div class="meta-row"><span>Status</span>
                    <span class="badge {{ $badge }}">{{ $balance <= 0 ? 'Paid' : str_replace('_', ' ', ucfirst($status)) }}</span>
                </div>
            </div>
        </div>

        <div class="body">
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
                    <p>Company No. {{ config('company.company_number') }}</p>
                    <p>{{ config('mail.from.address') }}</p>
                </div>
                <div class="block">
                    <h2>Bill To</h2>
                    <p><strong>{{ $invoicePayload['client_name'] ?: ($project->client?->name ?: '—') }}</strong></p>
                    @if($c = ($invoicePayload['client_company'] ?: ($project->client?->company ?: '')))
                        <p>{{ $c }}</p>
                    @endif
                    @if($e = ($invoicePayload['client_email'] ?: ($project->client?->email ?: '')))
                        <p>{{ $e }}</p>
                    @endif
                    @if($ph = ($invoicePayload['client_phone'] ?: ($project->client?->phone ?: '')))
                        <p>{{ $ph }}</p>
                    @endif
                </div>
            </div>

            <table class="table">
                <thead>
                <tr>
                    <th>Description</th>
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
                    <div class="totals-row"><span>Subtotal</span><strong>{{ $project->currency }} {{ number_format((float) $invoice->amount, 2) }}</strong></div>
                    <div class="totals-row"><span>Paid to date</span><strong>{{ $project->currency }} {{ number_format((float) $invoice->paid_amount, 2) }}</strong></div>
                    <div class="totals-row final"><span>{{ $balance <= 0 ? 'Settled' : 'Amount Due' }}</span><span>{{ $project->currency }} {{ number_format((float) $balance, 2) }}</span></div>
                </div>
            </div>

            @if($balance > 0)
                <div class="pay-grid" id="payment">
                    @if($hasBank)
                        <div class="pay-box">
                            <h3>Bank Transfer &middot; {{ $currency }}</h3>
                            <p class="hint">No processing fee. Please quote <strong>{{ $invoice->invoice_number }}</strong> as the reference.</p>
                            <div class="bank-row"><span>Account name</span><b>{{ $bank['account_name'] }}</b></div>
                            @if(filled($bank['bank'] ?? null))
                                <div class="bank-row"><span>Bank</span><b>{{ $bank['bank'] }}</b></div>
                            @endif
                            @foreach($bankFields as $label => $value)
                                <div class="bank-row"><span>{{ $label }}</span><b>{{ $value }}</b></div>
                            @endforeach
                            <div class="bank-row"><span>Reference</span><b>{{ $invoice->invoice_number }}</b></div>
                            @if(filled($interac))
                                <div class="bank-row"><span>Interac e-Transfer</span><b>{{ $interac }}</b></div>
                            @endif
                        </div>
                    @endif

                    @if(!$isPrintMode)
                        <div class="pay-box card">
                            <h3>Pay by Card</h3>
                            <p class="hint">Secure checkout via Stripe. Instant confirmation.</p>
                            @if($invoice->show_pay_button)
                                <form method="post" action="{{ route('invoice.public.pay', ['token' => $invoice->public_token]) }}" data-ga4-submit-event="begin_checkout" data-ga4-form-name="public_invoice_payment" data-ga4-value-field="amount" data-ga4-currency="{{ $project->currency }}">
                                    @csrf
                                    <div class="field">
                                        <label>Amount ({{ $project->currency }})</label>
                                        <input type="number" name="amount" min="0.01" step="0.01" value="{{ old('amount', number_format((float) $balance, 2, '.', '')) }}">
                                    </div>
                                    <div class="field">
                                        <label>Reference (optional)</label>
                                        <input type="text" name="reference" maxlength="120" value="{{ old('reference') }}">
                                    </div>
                                    <button type="submit" class="btn btn-primary">{{ $invoicePayload['payment_label'] ?: 'Pay Now' }}</button>
                                </form>
                            @else
                                <button type="button" class="btn btn-light" disabled>Online Payment Disabled</button>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            @if(!empty($invoicePayload['terms']))
                <div class="note"><strong>Terms.</strong> {{ $invoicePayload['terms'] }}</div>
            @endif
            @if(!empty($invoicePayload['extra_notes']))
                <div class="note"><strong>Note.</strong> {{ $invoicePayload['extra_notes'] }}</div>
            @endif
        </div>

        <div class="footer">
            {{ config('company.legal_name') }} &middot; Company No. {{ config('company.company_number') }} &middot; Registered in {{ config('company.registered_in') }}<br>
            {{ config('company.registered_office') }} &middot; {{ config('company.website') }}
        </div>
    </div>
</div>
@if($isPrintMode)
<script>window.addEventListener('load', function(){ window.print(); });</script>
@endif
</body>
</html>
