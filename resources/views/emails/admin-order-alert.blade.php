<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Order Payment Alert - {{ $invoice->invoice_number }}</title>
</head>
<body style="margin:0;padding:0;background:#f3f6fb;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f6fb;padding:18px 0;">
    <tr>
        <td align="center">
            <table role="presentation" width="640" cellpadding="0" cellspacing="0" style="max-width:640px;width:100%;background:#ffffff;border:1px solid #dbe5f5;border-radius:14px;overflow:hidden;">
                <tr>
                    <td style="padding:16px 20px;background:#133d7b;color:#ffffff;font-family:Arial,sans-serif;font-size:20px;font-weight:700;">
                        New Order Payment Received
                    </td>
                </tr>
                <tr>
                    <td style="padding:20px;font-family:Arial,sans-serif;color:#1a2b44;font-size:15px;line-height:1.6;">
                        <p style="margin:0 0 14px;">A payment has been recorded in your system.</p>
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;background:#f8fbff;border:1px solid #e1eaf8;border-radius:10px;">
                            <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Project</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $project->title }}</td></tr>
                            <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Client</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $project->client?->name ?: 'N/A' }} ({{ $project->client?->company ?: 'N/A' }})</td></tr>
                            <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Invoice #</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $invoice->invoice_number }}</td></tr>
                            <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Client Invoice Ref</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $invoice->client_invoice_number ?: 'N/A' }}</td></tr>
                            <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Payment Record ID</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">#{{ $payment->id }}</td></tr>
                            <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Gateway/Reference</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $payment->gateway_payment_id ?: ($payment->reference ?: 'N/A') }}</td></tr>
                            <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Method</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $payment->method ?: 'N/A' }}</td></tr>
                            <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Amount Paid</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $project->currency }} {{ number_format((float) $payment->amount, 2) }}</td></tr>
                            <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Total Paid on Invoice</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $project->currency }} {{ number_format((float) $invoice->paid_amount, 2) }}</td></tr>
                            <tr><td style="padding:10px 12px;"><strong>Invoice Balance</strong></td><td style="padding:10px 12px;">{{ $project->currency }} {{ number_format(max(0, (float) $invoice->amount - (float) $invoice->paid_amount), 2) }}</td></tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

