<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Invoice Link - {{ $invoice->invoice_number }}</title>
</head>
<body style="margin:0;padding:0;background:#f3f6fb;">
<div style="display:none;max-height:0;overflow:hidden;opacity:0;">
    Secure invoice link for {{ $invoice->invoice_number }}.
</div>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f6fb;padding:18px 0;">
    <tr>
        <td align="center">
            <table role="presentation" width="640" cellpadding="0" cellspacing="0" style="max-width:640px;width:100%;background:#ffffff;border:1px solid #dbe5f5;border-radius:14px;overflow:hidden;">
                <tr>
                    <td style="padding:16px 20px;background:#133d7b;color:#ffffff;font-family:Arial,sans-serif;font-size:20px;font-weight:700;">
                        ARS Developer - Secure Invoice Link
                    </td>
                </tr>
                <tr>
                    <td style="padding:20px;font-family:Arial,sans-serif;color:#1a2b44;font-size:15px;line-height:1.6;">
                        <p style="margin:0 0 12px;">
                            <img src="{{ url('/assets/images/resources/ars-logo-dark.png') }}" alt="ARS Developer" width="140" style="height:auto;display:block;">
                        </p>
                        <p style="margin:0 0 10px;">Hello {{ $project->client?->name ?: 'Client' }},</p>
                        <p style="margin:0 0 12px;">Please review your invoice and proceed with payment.</p>

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;background:#f8fbff;border:1px solid #e1eaf8;border-radius:10px;">
                            <tr>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;"><strong>Invoice</strong></td>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;">{{ $invoice->invoice_number }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;"><strong>Client Invoice Ref</strong></td>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;">{{ $invoice->client_invoice_number ?: 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;"><strong>Total</strong></td>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;">{{ $project->currency }} {{ number_format((float) $invoice->amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;"><strong>Paid</strong></td>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;">{{ $project->currency }} {{ number_format((float) $invoice->paid_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 14px;"><strong>Balance</strong></td>
                                <td style="padding:12px 14px;">{{ $project->currency }} {{ number_format((float) $balance, 2) }}</td>
                            </tr>
                        </table>

                        @if(!empty($invoicePayload['project_summary']))
                            <p style="margin:14px 0 0;"><strong>Project Summary:</strong> {{ $invoicePayload['project_summary'] }}</p>
                        @endif

                        <p style="margin:18px 0 10px;">
                            <a href="{{ $primaryUrl ?? $publicUrl }}" style="display:inline-block;background:#1f76d2;color:#ffffff;text-decoration:none;padding:10px 16px;border-radius:8px;font-weight:700;">{{ $primaryCta ?? 'Open Invoice & Pay' }}</a>
                        </p>
                        <p style="margin:0 0 16px;color:#556b8d;font-size:13px;">
                            Invoice link: <a href="{{ $primaryUrl ?? $publicUrl }}">{{ $primaryUrl ?? $publicUrl }}</a>
                        </p>

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
