<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Meeting Reminder</title>
</head>
<body style="margin:0;padding:0;background:#f3f6fb;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f3f6fb;padding:18px 0;">
    <tr>
        <td align="center">
            <table role="presentation" width="640" cellpadding="0" cellspacing="0" style="max-width:640px;width:100%;background:#ffffff;border:1px solid #dbe5f5;border-radius:14px;overflow:hidden;">
                <tr>
                    <td style="padding:16px 20px;background:#133d7b;color:#ffffff;font-family:Arial,sans-serif;font-size:20px;font-weight:700;">
                        ARS Developer - Meeting Reminder
                    </td>
                </tr>
                <tr>
                    <td style="padding:20px;font-family:Arial,sans-serif;color:#1a2b44;font-size:15px;line-height:1.6;">
                        <p style="margin:0 0 10px;">Hi {{ $lead->name ?: 'there' }},</p>
                        <p style="margin:0 0 14px;">
                            {{ $kind === '2h'
                                ? 'Quick reminder: your meeting starts in around 2 hours.'
                                : 'Quick reminder: your meeting is scheduled for tomorrow.' }}
                        </p>

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;background:#f8fbff;border:1px solid #e1eaf8;border-radius:10px;">
                            <tr>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;"><strong>Date</strong></td>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;">{{ $meetingStart->format('l, d M Y') }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;"><strong>Time</strong></td>
                                <td style="padding:12px 14px;border-bottom:1px solid #e8eef9;">{{ $meetingStart->format('h:i A') }} - {{ $meetingEnd->format('h:i A') }}</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 14px;"><strong>Timezone</strong></td>
                                <td style="padding:12px 14px;">{{ $lead->meeting_timezone ?: 'Europe/London' }}</td>
                            </tr>
                        </table>

                        @if(!empty($lead->meeting_token))
                            <p style="margin:14px 0 8px;">
                                <a href="{{ route('meeting.manage', ['token' => $lead->meeting_token]) }}" style="display:inline-block;background:#1182D8;color:#fff;text-decoration:none;padding:10px 14px;border-radius:8px;font-weight:700;margin-right:8px;">Reschedule</a>
                                <a href="{{ route('meeting.cancel', ['token' => $lead->meeting_token]) }}" style="display:inline-block;background:#ffffff;color:#133d7b;text-decoration:none;padding:10px 14px;border-radius:8px;font-weight:700;border:1px solid #cbdaf3;">Cancel Meeting</a>
                            </p>
                        @endif

                        <p style="margin:0;">If you need support, reply to this email or contact <a href="mailto:{{ config('contact.inbox_email') }}">{{ config('contact.inbox_email') }}</a>.</p>
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
