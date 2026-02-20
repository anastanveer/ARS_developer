<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ARSDeveloper | Request Received</title>
</head>
<body style="margin:0;padding:0;background:#eef3fb;">
@php
    $meetingEvent = (string) ($payload['meeting_event'] ?? 'booked');
    $isMeeting = (($payload['form_type'] ?? '') === 'meeting');
    $isOrder = (($payload['form_type'] ?? '') === 'pricing_order');
    $logoUrl = rtrim((string) config('app.url'), '/') . '/assets/images/resources/ars-logo-nav-white.png';
@endphp
<div style="display:none;max-height:0;overflow:hidden;opacity:0;">
    Your request is received. ARSDeveloper team will respond shortly.
</div>
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef3fb;padding:20px 8px;">
    <tr>
        <td align="center">
            <table role="presentation" width="680" cellpadding="0" cellspacing="0" style="max-width:680px;width:100%;background:#ffffff;border:1px solid #d6e2f5;border-radius:16px;overflow:hidden;">
                <tr>
                    <td style="background:#123f82;padding:18px 20px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="left" style="vertical-align:middle;">
                                    <img src="{{ $logoUrl }}" alt="ARSDeveloper" width="140" style="display:block;max-width:140px;height:auto;">
                                </td>
                                <td align="right" style="vertical-align:middle;font-family:Arial,sans-serif;color:#d7e7ff;font-size:13px;line-height:1.5;">
                                    UK Software Agency<br>{{ now()->format('d M Y, h:i A') }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding:22px 22px 10px 22px;font-family:Arial,sans-serif;color:#1b2d4d;">
                        <p style="margin:0 0 8px;font-size:24px;line-height:1.2;font-weight:800;color:#123b75;">
                            @if($isMeeting)
                                @if($meetingEvent === 'cancelled')
                                    Meeting Cancelled
                                @elseif($meetingEvent === 'rescheduled')
                                    Meeting Rescheduled
                                @else
                                    Meeting Confirmed
                                @endif
                            @elseif($isOrder)
                                Order Request Received
                            @else
                                Message Received Successfully
                            @endif
                        </p>

                        <p style="margin:0 0 14px;font-size:16px;line-height:1.6;color:#3f5478;">
                            Hi {{ $payload['name'] ?? 'there' }},
                            @if($isMeeting)
                                your meeting request is saved and confirmed in our system.
                            @elseif($isOrder)
                                your order request is in queue for invoice and kickoff setup.
                            @else
                                thank you for contacting ARSDeveloper. Our team will respond shortly.
                            @endif
                        </p>
                    </td>
                </tr>

                <tr>
                    <td style="padding:0 22px 8px 22px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border:1px solid #dce8f8;border-radius:12px;overflow:hidden;background:#f8fbff;">
                            <tr>
                                <td style="width:170px;padding:12px 14px;border-bottom:1px solid #e6eef9;font-family:Arial,sans-serif;font-size:14px;color:#173153;font-weight:700;vertical-align:top;">Subject</td>
                                <td style="padding:12px 14px;border-bottom:1px solid #e6eef9;font-family:Arial,sans-serif;font-size:14px;color:#2f466c;">{{ $payload['subject'] ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="width:170px;padding:12px 14px;font-family:Arial,sans-serif;font-size:14px;color:#173153;font-weight:700;vertical-align:top;">Message</td>
                                <td style="padding:12px 14px;font-family:Arial,sans-serif;font-size:14px;color:#2f466c;line-height:1.6;">{!! nl2br(e($payload['message'] ?? '-')) !!}</td>
                            </tr>
                        </table>
                    </td>
                </tr>

                @if(!empty($payload['coupon_code']) || is_numeric($payload['coupon_discount'] ?? null) || is_numeric($payload['final_quote_preview'] ?? null))
                    <tr>
                        <td style="padding:8px 22px 0 22px;">
                            <div style="border:1px solid #cde0fa;background:#f3f9ff;border-radius:10px;padding:12px 14px;font-family:Arial,sans-serif;">
                                <p style="margin:0 0 8px;font-size:14px;font-weight:800;color:#123b75;">Coupon Summary</p>
                                @if(!empty($payload['coupon_code']))
                                    <p style="margin:0 0 4px;font-size:14px;color:#2f466c;"><strong>Code:</strong> {{ strtoupper((string) $payload['coupon_code']) }}</p>
                                @endif
                                @if(is_numeric($payload['coupon_discount'] ?? null))
                                    <p style="margin:0 0 4px;font-size:14px;color:#2f466c;"><strong>Discount:</strong> GBP {{ number_format((float) $payload['coupon_discount'], 2) }}</p>
                                @endif
                                @if(is_numeric($payload['final_quote_preview'] ?? null))
                                    <p style="margin:0;font-size:14px;color:#2f466c;"><strong>Final Amount Preview:</strong> GBP {{ number_format((float) $payload['final_quote_preview'], 2) }}</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endif

                @if($isOrder)
                    <tr>
                        <td style="padding:8px 22px 0 22px;">
                            <div style="border:1px solid #d6e5fa;background:#fbfdff;border-radius:10px;padding:12px 14px;font-family:Arial,sans-serif;">
                                <p style="margin:0 0 8px;font-size:14px;font-weight:800;color:#123b75;">Next Steps</p>
                                <p style="margin:0 0 4px;font-size:14px;color:#2f466c;">1) Scope + coupon validation by ARS team</p>
                                <p style="margin:0 0 4px;font-size:14px;color:#2f466c;">2) Kickoff invoice/payment link on your email</p>
                                <p style="margin:0;font-size:14px;color:#2f466c;">3) After payment, client portal link/token delivery</p>
                            </div>
                        </td>
                    </tr>
                @endif

                @if($isMeeting)
                    <tr>
                        <td style="padding:8px 22px 0 22px;">
                            <div style="border:1px solid #d6e5fa;background:#fbfdff;border-radius:10px;padding:12px 14px;font-family:Arial,sans-serif;">
                                <p style="margin:0 0 8px;font-size:14px;font-weight:800;color:#123b75;">Meeting Details</p>
                                <p style="margin:0 0 4px;font-size:14px;color:#2f466c;"><strong>Reference:</strong> {{ $payload['meeting_reference'] ?? '-' }}</p>
                                <p style="margin:0 0 4px;font-size:14px;color:#2f466c;"><strong>Date:</strong> {{ $payload['meeting_date_label'] ?? ($payload['meeting_date'] ?? '-') }}</p>
                                <p style="margin:0 0 4px;font-size:14px;color:#2f466c;"><strong>Time:</strong> {{ $payload['meeting_time_label'] ?? ($payload['meeting_slot'] ?? '-') }}</p>
                                <p style="margin:0;font-size:14px;color:#2f466c;"><strong>Timezone:</strong> {{ $payload['meeting_timezone'] ?? 'Europe/London' }}</p>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:10px 22px 0 22px;font-family:Arial,sans-serif;">
                            <a href="{{ $payload['meeting_manage_url'] ?? '#' }}" style="display:inline-block;background:#117fd7;color:#ffffff;text-decoration:none;padding:10px 14px;border-radius:8px;font-size:14px;font-weight:700;margin-right:8px;">Manage Booking</a>
                            <a href="{{ $payload['meeting_cancel_url'] ?? '#' }}" style="display:inline-block;background:#ffffff;color:#123b75;text-decoration:none;padding:10px 14px;border-radius:8px;border:1px solid #c9daf2;font-size:14px;font-weight:700;">Cancel Slot</a>
                        </td>
                    </tr>
                @endif

                <tr>
                    <td style="padding:16px 22px 12px 22px;font-family:Arial,sans-serif;color:#3f5478;font-size:14px;line-height:1.7;">
                        For urgent support, email us at
                        <a href="mailto:{{ config('contact.inbox_email') }}" style="color:#117fd7;text-decoration:underline;">{{ config('contact.inbox_email') }}</a>.
                        <br><br>
                        Regards,<br>
                        <strong>Director</strong><br>
                        {{ config('company.legal_name') }}<br>
                        Company No: {{ config('company.company_number') }}<br>
                        Registered in {{ config('company.registered_in') }}
                    </td>
                </tr>

                <tr>
                    <td style="background:#f6f9ff;border-top:1px solid #e3ecfa;padding:12px 22px;font-family:Arial,sans-serif;color:#61789c;font-size:12px;line-height:1.6;">
                        © {{ now()->year }} {{ config('company.legal_name') }} · Company No: {{ config('company.company_number') }} · {{ config('company.registered_in') }}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
