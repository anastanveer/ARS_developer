<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>New Contact Query</title>
</head>
<body style="margin:0;padding:0;background:#f3f6fb;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f6fb;padding:18px 0;">
    <tr>
        <td align="center">
            <table role="presentation" width="680" cellpadding="0" cellspacing="0" style="max-width:680px;width:100%;background:#ffffff;border:1px solid #dbe5f5;border-radius:14px;overflow:hidden;">
                <tr>
                    <td style="padding:16px 20px;background:#133d7b;color:#ffffff;font-family:Arial,sans-serif;font-size:20px;font-weight:700;">
                        New Contact Query Received
                    </td>
                </tr>
                <tr>
                    <td style="padding:20px;font-family:Arial,sans-serif;color:#1a2b44;font-size:14px;line-height:1.6;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;background:#f8fbff;border:1px solid #e1eaf8;border-radius:10px;">
                            <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Name</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $payload['name'] ?? '' }}</td></tr>
                            <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Email</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $payload['email'] ?? '' }}</td></tr>
                            @if(!empty($payload['phone']))
                                <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Phone</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $payload['phone'] }}</td></tr>
                            @endif
                            @if(!empty($payload['company']))
                                <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Company</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $payload['company'] }}</td></tr>
                            @endif
                            @if(!empty($payload['project_type']))
                                <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Project Type</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $payload['project_type'] }}</td></tr>
                            @endif
                            @if(!empty($payload['budget_range']))
                                <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Budget Range</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $payload['budget_range'] }}</td></tr>
                            @endif
                            @if(!empty($payload['coupon_code']) || !empty($payload['coupon_discount']) || !empty($payload['final_quote_preview']))
                                @if(!empty($payload['coupon_code']))
                                    <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Coupon Code</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ strtoupper((string) $payload['coupon_code']) }}</td></tr>
                                @endif
                                @if(is_numeric($payload['coupon_discount'] ?? null))
                                    <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Coupon Discount</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">GBP {{ number_format((float) $payload['coupon_discount'], 2) }}</td></tr>
                                @endif
                                @if(is_numeric($payload['final_quote_preview'] ?? null))
                                    <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Final Quote Preview</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">GBP {{ number_format((float) $payload['final_quote_preview'], 2) }}</td></tr>
                                @endif
                            @endif
                            @if(!empty($payload['meeting_date']))
                                <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Preferred Date</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $payload['meeting_date'] }}</td></tr>
                            @endif
                            @if(!empty($payload['meeting_slot']))
                                <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Preferred Time Slot</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $payload['meeting_slot'] }}</td></tr>
                            @endif
                            @if(!empty($payload['meeting_timezone']))
                                <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Meeting Timezone</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $payload['meeting_timezone'] }}</td></tr>
                            @endif
                            @if(!empty($payload['meeting_reference']))
                                <tr><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;"><strong>Meeting Reference</strong></td><td style="padding:10px 12px;border-bottom:1px solid #e8eef9;">{{ $payload['meeting_reference'] }}</td></tr>
                            @endif
                            <tr><td style="padding:10px 12px;"><strong>Subject</strong></td><td style="padding:10px 12px;">{{ $payload['subject'] ?? '' }}</td></tr>
                        </table>

                        @if(!empty($payload['meeting_manage_url']) || !empty($payload['meeting_confirmation_url']))
                            <p style="margin:12px 0 6px;"><strong>Client Self-Service Links</strong></p>
                            @if(!empty($payload['meeting_confirmation_url']))
                                <p style="margin:0;"><a href="{{ $payload['meeting_confirmation_url'] }}">Confirmation Page</a></p>
                            @endif
                            @if(!empty($payload['meeting_manage_url']))
                                <p style="margin:0;"><a href="{{ $payload['meeting_manage_url'] }}">Manage Booking</a></p>
                            @endif
                            @if(!empty($payload['meeting_cancel_url']))
                                <p style="margin:0 0 8px;"><a href="{{ $payload['meeting_cancel_url'] }}">Cancel Link</a></p>
                            @endif
                        @endif

                        <p style="margin:14px 0 6px;"><strong>Message</strong></p>
                        <div style="padding:12px;border:1px solid #e1eaf8;background:#fbfdff;border-radius:8px;">{!! nl2br(e($payload['message'] ?? '')) !!}</div>

                        <p style="margin:16px 0 6px;"><strong>Submission Metadata</strong></p>
                        <p style="margin:0;"><strong>Submitted At:</strong> {{ $payload['submitted_at'] ?? '' }}</p>
                        <p style="margin:0;"><strong>Submitted From:</strong> {{ $payload['submitted_from'] ?? '' }}</p>
                        <p style="margin:0;"><strong>IP Address:</strong> {{ $payload['ip'] ?? '' }}</p>
                        <p style="margin:0;"><strong>User Agent:</strong> {{ $payload['user_agent'] ?? '' }}</p>

                        <div style="margin-top:14px;padding-top:10px;border-top:1px dashed #d9e6fa;">
                            <p style="margin:0;">&copy; {{ now()->year }} {{ config('company.legal_name') }}.</p>
                            <p style="margin:4px 0 0;">Company No: {{ config('company.company_number') }} | Registered in {{ config('company.registered_in') }}</p>
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
