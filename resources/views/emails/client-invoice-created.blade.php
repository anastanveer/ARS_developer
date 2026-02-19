<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>New Invoice - {{ $invoice->invoice_number }}</title>
</head>
<body style="margin:0;padding:0;background:#f3f6fb;">
<div style="display:none;max-height:0;overflow:hidden;opacity:0;">
    New invoice {{ $invoice->invoice_number }} for {{ $project->title }} is now available.
</div>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f6fb;padding:18px 0;">
    <tr>
        <td align="center">
            <table role="presentation" width="640" cellpadding="0" cellspacing="0" style="max-width:640px;width:100%;background:#ffffff;border:1px solid #dbe5f5;border-radius:14px;overflow:hidden;">
                <tr>
                    <td style="padding:16px 20px;background:#133d7b;color:#ffffff;font-family:Arial,sans-serif;font-size:20px;font-weight:700;">
                        ARS Developer - Invoice Update
                    </td>
                </tr>
                <tr>
                    <td style="padding:20px;font-family:Arial,sans-serif;color:#1a2b44;font-size:15px;line-height:1.6;">
                        <p style="margin:0 0 10px;">Hello {{ $project->client?->name ?: 'Client' }},</p>
                        <p style="margin:0 0 16px;">A new invoice has been generated for your project <strong>{{ $project->title }}</strong>.</p>

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;background:#f8fbff;border:1px solid #e1eaf8;border-radius:10px;">
                            <tr>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;"><strong>Invoice</strong></td>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;">{{ $invoice->invoice_number }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;"><strong>Date</strong></td>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;">{{ optional($invoice->invoice_date)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;"><strong>Due Date</strong></td>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;">{{ optional($invoice->due_date)->format('d M Y') ?: 'Not specified' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;"><strong>Amount</strong></td>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;">{{ $project->currency }} {{ number_format((float) $invoice->amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 14px;"><strong>Status</strong></td>
                                <td style="padding:12px 14px;">{{ str_replace('_', ' ', ucfirst($invoice->status)) }}</td>
                            </tr>
                        </table>

                        @if(!empty($invoice->notes))
                            <p style="margin:14px 0 0;"><strong>Notes:</strong> {{ $invoice->notes }}</p>
                        @endif

                        <p style="margin:18px 0 10px;">Review invoice, timeline, and requirements in your client portal:</p>
                        <p style="margin:0 0 14px;">
                            <a href="{{ $portalUrl }}" style="display:inline-block;background:#1f76d2;color:#ffffff;text-decoration:none;padding:10px 16px;border-radius:8px;font-weight:700;">Open Client Portal</a>
                        </p>
                        <p style="margin:0 0 6px;"><strong>Quick access guide:</strong></p>
                        <p style="margin:0 0 4px;">1) Click the button above, or open <a href="{{ url('/client-portal-access') }}">{{ url('/client-portal-access') }}</a></p>
                        <p style="margin:0 0 16px;">2) Paste the full portal link, or token after <code>/client-portal/</code></p>

                        <p style="margin:0;">Regards,<br>Director<br>{{ config('company.legal_name') }}<br>Company No: {{ config('company.company_number') }}<br>Registered in {{ config('company.registered_in') }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:14px 20px;background:#f8fbff;border-top:1px solid #e5edf9;font-family:Arial,sans-serif;color:#526a8d;font-size:12px;line-height:1.5;">
                        <div>&copy; {{ now()->year }} {{ config('company.legal_name') }}.</div>
                        <div>Company No: {{ config('company.company_number') }} | Registered in {{ config('company.registered_in') }}</div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
