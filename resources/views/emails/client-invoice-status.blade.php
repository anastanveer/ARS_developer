<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Invoice Status Update - {{ $invoice->invoice_number }}</title>
</head>
<body style="margin:0;padding:0;background:#f3f6fb;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f6fb;padding:18px 0;">
    <tr>
        <td align="center">
            <table role="presentation" width="640" cellpadding="0" cellspacing="0" style="max-width:640px;width:100%;background:#ffffff;border:1px solid #dbe5f5;border-radius:14px;overflow:hidden;">
                <tr>
                    <td style="padding:16px 20px;background:#133d7b;color:#ffffff;font-family:Arial,sans-serif;font-size:20px;font-weight:700;">
                        ARS Developer - Invoice Status Update
                    </td>
                </tr>
                <tr>
                    <td style="padding:20px;font-family:Arial,sans-serif;color:#1a2b44;font-size:15px;line-height:1.6;">
                        <p style="margin:0 0 12px;">
                            <img src="{{ url('/assets/images/resources/ars-logo-dark.png') }}" alt="ARS Developer" width="140" style="height:auto;display:block;">
                        </p>
                        <p style="margin:0 0 10px;">Hello {{ $project->client?->name ?: 'Client' }},</p>
                        <p style="margin:0 0 14px;">Invoice <strong>{{ $invoice->invoice_number }}</strong> status has been updated.</p>

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;background:#f8fbff;border:1px solid #e1eaf8;border-radius:10px;">
                            <tr>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;"><strong>Project</strong></td>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;">{{ $project->title }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;"><strong>Previous Status</strong></td>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;">{{ str_replace('_', ' ', ucfirst($oldStatus)) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;"><strong>Current Status</strong></td>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;">{{ $statusLabel }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;"><strong>Amount</strong></td>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;">{{ $project->currency }} {{ number_format((float) $invoice->amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;"><strong>Paid</strong></td>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;">{{ $project->currency }} {{ number_format((float) $invoice->paid_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 14px;"><strong>Balance</strong></td>
                                <td style="padding:12px 14px;">{{ $project->currency }} {{ number_format(max(0, (float) $invoice->amount - (float) $invoice->paid_amount), 2) }}</td>
                            </tr>
                        </table>

                        <p style="margin:14px 0 0;">{{ $statusText }}</p>
                        @if(!empty($note))
                            <p style="margin:8px 0 0;"><strong>Admin Note:</strong> {{ $note }}</p>
                        @endif

                        @if(!empty($paymentUrl) && in_array($invoice->status, ['pending','unpaid','overdue','late','failed'], true))
                            <p style="margin:16px 0 10px;">
                                <a href="{{ $paymentUrl }}" style="display:inline-block;background:#1f76d2;color:#ffffff;text-decoration:none;padding:10px 16px;border-radius:8px;font-weight:700;">Pay Now</a>
                            </p>
                        @endif
                        <p style="margin:0 0 16px;color:#556b8d;font-size:13px;">Invoice link: <a href="{{ $invoiceUrl }}">{{ $invoiceUrl }}</a></p>

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
