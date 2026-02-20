<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Thank You - {{ $invoice->invoice_number }}</title>
</head>
<body style="margin:0;padding:0;background:#f3f6fb;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f6fb;padding:18px 0;">
    <tr>
        <td align="center">
            <table role="presentation" width="640" cellpadding="0" cellspacing="0" style="max-width:640px;width:100%;background:#ffffff;border:1px solid #dbe5f5;border-radius:14px;overflow:hidden;">
                <tr>
                    <td style="padding:16px 20px;background:#133d7b;color:#ffffff;font-family:Arial,sans-serif;font-size:20px;font-weight:700;">
                        Thank You for Your Order
                    </td>
                </tr>
                <tr>
                    <td style="padding:20px;font-family:Arial,sans-serif;color:#1a2b44;font-size:15px;line-height:1.6;">
                        <p style="margin:0 0 12px;">Hello {{ $project->client?->name ?: 'Client' }},</p>
                        <p style="margin:0 0 14px;">Thank you. Your payment has been received and this invoice is now marked as paid.</p>

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;background:#f8fbff;border:1px solid #e1eaf8;border-radius:10px;">
                            <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Project</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $project->title }}</td></tr>
                            <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Invoice #</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $invoice->invoice_number }}</td></tr>
                            <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Client Invoice Ref</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $invoice->client_invoice_number ?: 'N/A' }}</td></tr>
                            <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Payment Record ID</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">#{{ $payment->id }}</td></tr>
                            <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Amount Paid</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $project->currency }} {{ number_format((float) $payment->amount, 2) }}</td></tr>
                            <tr><td style="padding:10px 12px;"><strong>Payment Reference</strong></td><td style="padding:10px 12px;">{{ $payment->gateway_payment_id ?: ($payment->reference ?: 'N/A') }}</td></tr>
                        </table>

                        <p style="margin:18px 0 10px;">Your feedback helps us improve and builds trust for new clients.</p>
                        <p style="margin:0 0 14px;">
                            <a href="{{ $reviewUrl }}" style="display:inline-block;background:#1f76d2;color:#ffffff;text-decoration:none;padding:10px 16px;border-radius:8px;font-weight:700;">Leave a Review</a>
                        </p>

                        <p style="margin:0 0 8px;">You can also track your milestones and history any time:</p>
                        <p style="margin:0 0 14px;"><a href="{{ $portalUrl }}">{{ $portalUrl }}</a></p>

                        <p style="margin:0;">Regards,<br>Director<br>{{ config('company.legal_name') }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>

