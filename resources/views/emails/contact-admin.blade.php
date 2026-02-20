<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ARSDeveloper | New Lead Alert</title>
</head>
<body style="margin:0;padding:0;background:#eef3fb;">
@php
    $logoUrl = rtrim((string) config('app.url'), '/') . '/assets/images/resources/ars-logo-nav-white.png';
@endphp
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#eef3fb;padding:20px 8px;">
    <tr>
        <td align="center">
            <table role="presentation" width="700" cellpadding="0" cellspacing="0" style="max-width:700px;width:100%;background:#ffffff;border:1px solid #d6e2f5;border-radius:16px;overflow:hidden;">
                <tr>
                    <td style="background:#0f376f;padding:18px 20px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
                            <tr>
                                <td align="left" style="vertical-align:middle;">
                                    <img src="{{ $logoUrl }}" alt="ARSDeveloper" width="140" style="display:block;max-width:140px;height:auto;">
                                </td>
                                <td align="right" style="vertical-align:middle;font-family:Arial,sans-serif;color:#d6e6ff;font-size:13px;line-height:1.5;">
                                    New Lead Alert<br>{{ now()->format('d M Y, h:i A') }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr>
                    <td style="padding:20px 22px 10px 22px;font-family:Arial,sans-serif;color:#1b2d4d;">
                        <p style="margin:0 0 8px;font-size:23px;font-weight:800;color:#123b75;">New Contact/Order Submission</p>
                        <p style="margin:0;font-size:14px;line-height:1.6;color:#3f5478;">A new lead has been received from website forms. Review details and follow up quickly.</p>
                    </td>
                </tr>

                <tr>
                    <td style="padding:10px 22px 8px 22px;">
                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;border:1px solid #dce8f8;border-radius:12px;overflow:hidden;background:#f8fbff;">
                            <tr><td style="padding:11px 13px;border-bottom:1px solid #e6eef9;font-family:Arial,sans-serif;font-size:14px;font-weight:700;color:#173153;width:180px;">Name</td><td style="padding:11px 13px;border-bottom:1px solid #e6eef9;font-family:Arial,sans-serif;font-size:14px;color:#2f466c;">{{ $payload['name'] ?? '-' }}</td></tr>
                            <tr><td style="padding:11px 13px;border-bottom:1px solid #e6eef9;font-family:Arial,sans-serif;font-size:14px;font-weight:700;color:#173153;">Email</td><td style="padding:11px 13px;border-bottom:1px solid #e6eef9;font-family:Arial,sans-serif;font-size:14px;color:#2f466c;">{{ $payload['email'] ?? '-' }}</td></tr>
                            <tr><td style="padding:11px 13px;border-bottom:1px solid #e6eef9;font-family:Arial,sans-serif;font-size:14px;font-weight:700;color:#173153;">Form Type</td><td style="padding:11px 13px;border-bottom:1px solid #e6eef9;font-family:Arial,sans-serif;font-size:14px;color:#2f466c;">{{ strtoupper((string) ($payload['form_type'] ?? 'contact')) }}</td></tr>
                            @if(!empty($payload['phone']))
                                <tr><td style="padding:11px 13px;border-bottom:1px solid #e6eef9;font-family:Arial,sans-serif;font-size:14px;font-weight:700;color:#173153;">Phone</td><td style="padding:11px 13px;border-bottom:1px solid #e6eef9;font-family:Arial,sans-serif;font-size:14px;color:#2f466c;">{{ $payload['phone'] }}</td></tr>
                            @endif
                            @if(!empty($payload['company']))
                                <tr><td style="padding:11px 13px;border-bottom:1px solid #e6eef9;font-family:Arial,sans-serif;font-size:14px;font-weight:700;color:#173153;">Company</td><td style="padding:11px 13px;border-bottom:1px solid #e6eef9;font-family:Arial,sans-serif;font-size:14px;color:#2f466c;">{{ $payload['company'] }}</td></tr>
                            @endif
                            @if(!empty($payload['project_type']))
                                <tr><td style="padding:11px 13px;border-bottom:1px solid #e6eef9;font-family:Arial,sans-serif;font-size:14px;font-weight:700;color:#173153;">Project Type</td><td style="padding:11px 13px;border-bottom:1px solid #e6eef9;font-family:Arial,sans-serif;font-size:14px;color:#2f466c;">{{ $payload['project_type'] }}</td></tr>
                            @endif
                            @if(!empty($payload['budget_range']))
                                <tr><td style="padding:11px 13px;border-bottom:1px solid #e6eef9;font-family:Arial,sans-serif;font-size:14px;font-weight:700;color:#173153;">Budget Range</td><td style="padding:11px 13px;border-bottom:1px solid #e6eef9;font-family:Arial,sans-serif;font-size:14px;color:#2f466c;">{{ $payload['budget_range'] }}</td></tr>
                            @endif
                            <tr><td style="padding:11px 13px;font-family:Arial,sans-serif;font-size:14px;font-weight:700;color:#173153;">Subject</td><td style="padding:11px 13px;font-family:Arial,sans-serif;font-size:14px;color:#2f466c;">{{ $payload['subject'] ?? '-' }}</td></tr>
                        </table>
                    </td>
                </tr>

                @if(!empty($payload['coupon_code']) || is_numeric($payload['coupon_discount'] ?? null) || is_numeric($payload['final_quote_preview'] ?? null))
                    <tr>
                        <td style="padding:8px 22px 0 22px;">
                            <div style="border:1px solid #cde0fa;background:#f3f9ff;border-radius:10px;padding:12px 14px;font-family:Arial,sans-serif;">
                                <p style="margin:0 0 8px;font-size:14px;font-weight:800;color:#123b75;">Coupon Details</p>
                                @if(!empty($payload['coupon_code']))
                                    <p style="margin:0 0 4px;font-size:14px;color:#2f466c;"><strong>Code:</strong> {{ strtoupper((string) $payload['coupon_code']) }}</p>
                                @endif
                                @if(is_numeric($payload['coupon_discount'] ?? null))
                                    <p style="margin:0 0 4px;font-size:14px;color:#2f466c;"><strong>Discount:</strong> GBP {{ number_format((float) $payload['coupon_discount'], 2) }}</p>
                                @endif
                                @if(is_numeric($payload['final_quote_preview'] ?? null))
                                    <p style="margin:0;font-size:14px;color:#2f466c;"><strong>Final Quote Preview:</strong> GBP {{ number_format((float) $payload['final_quote_preview'], 2) }}</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endif

                @if(!empty($payload['meeting_date']) || !empty($payload['meeting_slot']) || !empty($payload['meeting_timezone']))
                    <tr>
                        <td style="padding:8px 22px 0 22px;">
                            <div style="border:1px solid #d6e5fa;background:#fbfdff;border-radius:10px;padding:12px 14px;font-family:Arial,sans-serif;">
                                <p style="margin:0 0 8px;font-size:14px;font-weight:800;color:#123b75;">Meeting Details</p>
                                @if(!empty($payload['meeting_reference']))
                                    <p style="margin:0 0 4px;font-size:14px;color:#2f466c;"><strong>Reference:</strong> {{ $payload['meeting_reference'] }}</p>
                                @endif
                                @if(!empty($payload['meeting_date']))
                                    <p style="margin:0 0 4px;font-size:14px;color:#2f466c;"><strong>Date:</strong> {{ $payload['meeting_date'] }}</p>
                                @endif
                                @if(!empty($payload['meeting_slot']))
                                    <p style="margin:0 0 4px;font-size:14px;color:#2f466c;"><strong>Slot:</strong> {{ $payload['meeting_slot'] }}</p>
                                @endif
                                @if(!empty($payload['meeting_timezone']))
                                    <p style="margin:0;font-size:14px;color:#2f466c;"><strong>Timezone:</strong> {{ $payload['meeting_timezone'] }}</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endif

                <tr>
                    <td style="padding:10px 22px 6px 22px;font-family:Arial,sans-serif;">
                        <p style="margin:0 0 6px;font-size:14px;font-weight:800;color:#123b75;">Message</p>
                        <div style="padding:12px 14px;border:1px solid #dce8f8;background:#ffffff;border-radius:10px;font-family:Arial,sans-serif;font-size:14px;line-height:1.6;color:#2f466c;">{!! nl2br(e($payload['message'] ?? '-')) !!}</div>
                    </td>
                </tr>

                <tr>
                    <td style="padding:8px 22px 14px 22px;font-family:Arial,sans-serif;font-size:13px;color:#5b7398;line-height:1.7;">
                        <strong>Metadata:</strong><br>
                        Submitted At: {{ $payload['submitted_at'] ?? '-' }}<br>
                        Source: {{ $payload['submitted_from'] ?? '-' }}<br>
                        IP: {{ $payload['ip'] ?? '-' }}<br>
                        Country: {{ $payload['country'] ?? '-' }}
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
