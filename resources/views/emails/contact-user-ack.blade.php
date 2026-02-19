<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Thanks for contacting ARSDeveloper</title>
</head>
<body style="margin:0;padding:0;background:#f3f6fb;">
<div style="display:none;max-height:0;overflow:hidden;opacity:0;">
    We received your message and our team will respond shortly.
</div>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f6fb;padding:18px 0;">
    <tr>
        <td align="center">
            <table role="presentation" width="640" cellpadding="0" cellspacing="0" style="max-width:640px;width:100%;background:#ffffff;border:1px solid #dbe5f5;border-radius:14px;overflow:hidden;">
                <tr>
                    <td style="padding:16px 20px;background:#133d7b;color:#ffffff;font-family:Arial,sans-serif;font-size:20px;font-weight:700;">
                        ARS Developer - {{ ($payload['form_type'] ?? '') === 'meeting' ? 'Meeting Update' : 'Message Received' }}
                    </td>
                </tr>
                <tr>
                    <td style="padding:20px;font-family:Arial,sans-serif;color:#1a2b44;font-size:15px;line-height:1.6;">
                        @php($meetingEvent = (string) ($payload['meeting_event'] ?? 'booked'))
                        <p style="margin:0 0 10px;">Hi {{ $payload['name'] ?? 'there' }},</p>
                        @if(($payload['form_type'] ?? '') === 'meeting')
                            @if($meetingEvent === 'cancelled')
                                <p style="margin:0 0 14px;">Your meeting has been cancelled successfully. You can reschedule anytime from your manage link below.</p>
                            @elseif($meetingEvent === 'rescheduled')
                                <p style="margin:0 0 14px;">Your meeting has been rescheduled successfully. Updated details are below.</p>
                            @else
                                <p style="margin:0 0 14px;">Your meeting is booked successfully. Your slot is now locked in our calendar.</p>
                            @endif
                        @else
                            <p style="margin:0 0 14px;">Thank you for contacting ARS Developer. We have received your request and our team will get back to you shortly.</p>
                        @endif

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;background:#f8fbff;border:1px solid #e1eaf8;border-radius:10px;">
                            <tr>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;"><strong>Your Subject</strong></td>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;">{{ $payload['subject'] ?? '' }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 14px;vertical-align:top;"><strong>Your Message</strong></td>
                                <td style="padding:12px 14px;">{!! nl2br(e($payload['message'] ?? '')) !!}</td>
                            </tr>
                        </table>
                        @if(!empty($payload['coupon_code']) || is_numeric($payload['coupon_discount'] ?? null) || is_numeric($payload['final_quote_preview'] ?? null))
                            <div style="padding:12px;border:1px solid #d9e7fb;background:#f6faff;border-radius:8px;margin:12px 0 0;">
                                <p style="margin:0 0 6px;font-weight:700;">Coupon Summary</p>
                                @if(!empty($payload['coupon_code']))
                                    <p style="margin:0;"><strong>Code:</strong> {{ strtoupper((string) $payload['coupon_code']) }}</p>
                                @endif
                                @if(is_numeric($payload['coupon_discount'] ?? null))
                                    <p style="margin:0;"><strong>Discount:</strong> GBP {{ number_format((float) $payload['coupon_discount'], 2) }}</p>
                                @endif
                                @if(is_numeric($payload['final_quote_preview'] ?? null))
                                    <p style="margin:0;"><strong>Final Amount Preview:</strong> GBP {{ number_format((float) $payload['final_quote_preview'], 2) }}</p>
                                @endif
                                <p style="margin:6px 0 0;">This discount is finalized after admin validation and invoice confirmation.</p>
                            </div>
                        @endif

                        @if(($payload['form_type'] ?? '') === 'meeting')
                            <p style="margin:14px 0 6px;"><strong>Meeting request details</strong></p>
                            <p style="margin:0;"><strong>Reference:</strong> {{ $payload['meeting_reference'] ?? '-' }}</p>
                            <p style="margin:0;"><strong>Date:</strong> {{ $payload['meeting_date_label'] ?? ($payload['meeting_date'] ?? '') }}</p>
                            <p style="margin:0;"><strong>Time:</strong> {{ $payload['meeting_time_label'] ?? ($payload['meeting_slot'] ?? '') }}</p>
                            <p style="margin:0 0 10px;"><strong>Timezone:</strong> {{ $payload['meeting_timezone'] ?? 'Europe/London' }}</p>

                            @if(!empty($payload['meeting_previous_date']) && !empty($payload['meeting_previous_slot']) && $meetingEvent === 'rescheduled')
                                <p style="margin:0 0 10px;"><strong>Previous Slot:</strong> {{ $payload['meeting_previous_date'] }} | {{ $payload['meeting_previous_slot'] }}</p>
                            @endif

                            <p style="margin:0 0 8px;">
                                <a href="{{ $payload['meeting_manage_url'] ?? '#' }}" style="display:inline-block;background:#1182D8;color:#fff;text-decoration:none;padding:10px 14px;border-radius:8px;font-weight:700;margin-right:8px;">Reschedule</a>
                                <a href="{{ $payload['meeting_cancel_url'] ?? '#' }}" style="display:inline-block;background:#ffffff;color:#133d7b;text-decoration:none;padding:10px 14px;border-radius:8px;font-weight:700;border:1px solid #cbdaf3;">Cancel</a>
                            </p>

                            <div style="padding:10px 12px;border:1px solid #d9e7fb;background:#f6faff;border-radius:8px;margin:8px 0 14px;">
                                <p style="margin:0 0 6px;font-weight:700;">Please prepare before the call:</p>
                                <p style="margin:0;">1) Website URL, 2) Primary goal (qualified leads/sales growth/operations), 3) Biggest challenge, 4) Budget range and timeline.</p>
                            </div>
                        @endif

                        <p style="margin:0;">For urgent matters, email us at <a href="mailto:{{ config('contact.inbox_email') }}">{{ config('contact.inbox_email') }}</a>.</p>
                        <p style="margin:14px 0 0;">Regards,<br>Director<br>{{ config('company.legal_name') }}<br>Company No: {{ config('company.company_number') }}<br>Registered in {{ config('company.registered_in') }}</p>
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
