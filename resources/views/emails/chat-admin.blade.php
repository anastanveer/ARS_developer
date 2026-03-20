<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>New Website Chat</title>
</head>
<body style="margin:0;padding:0;background:#f4f8ff;font-family:Arial,sans-serif;color:#173153;">
    <div style="max-width:700px;margin:0 auto;padding:28px 18px;">
        <div style="background:#ffffff;border:1px solid #dce7f8;border-radius:18px;overflow:hidden;">
            <div style="padding:22px 24px;background:linear-gradient(135deg,#10356b,#1670c4);color:#fff;">
                <div style="font-size:24px;font-weight:700;">New Website Chat Message</div>
                <div style="margin-top:8px;font-size:14px;opacity:.9;">A visitor started or continued a live chat conversation.</div>
            </div>
            <div style="padding:22px 24px;">
                <table style="width:100%;border-collapse:collapse;">
                    <tr>
                        <td style="padding:10px 0;border-bottom:1px solid #e8eef9;font-weight:700;width:160px;">Name</td>
                        <td style="padding:10px 0;border-bottom:1px solid #e8eef9;">{{ $conversation->name ?: 'Website Visitor' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:10px 0;border-bottom:1px solid #e8eef9;font-weight:700;">Email</td>
                        <td style="padding:10px 0;border-bottom:1px solid #e8eef9;">{{ $conversation->email ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:10px 0;border-bottom:1px solid #e8eef9;font-weight:700;">Phone / WhatsApp</td>
                        <td style="padding:10px 0;border-bottom:1px solid #e8eef9;">{{ $conversation->phone ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:10px 0;border-bottom:1px solid #e8eef9;font-weight:700;">Page</td>
                        <td style="padding:10px 0;border-bottom:1px solid #e8eef9;">{{ $conversation->source_page ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td style="padding:10px 0 0;font-weight:700;vertical-align:top;">Message</td>
                        <td style="padding:10px 0 0;line-height:1.7;">{{ $message->body }}</td>
                    </tr>
                </table>

                <div style="margin-top:24px;">
                    <a href="{{ route('admin.chat.index', ['conversation' => $conversation->id]) }}" style="display:inline-block;padding:12px 18px;border-radius:999px;background:#0f63b8;color:#fff;text-decoration:none;font-weight:700;">Open Admin Inbox</a>
                    @if($conversation->phone)
                        @php
                            $waDigits = preg_replace('/\D+/', '', (string) $conversation->phone);
                            $waText = rawurlencode('Hi ' . ($conversation->name ?: 'there') . ', thanks for your message on ARSDeveloper. Our team is following up now.');
                        @endphp
                        <a href="https://wa.me/{{ $waDigits }}?text={{ $waText }}" style="display:inline-block;padding:12px 18px;border-radius:999px;background:#ffffff;color:#0f63b8;text-decoration:none;font-weight:700;border:1px solid #cfe0fb;margin-left:10px;">Open WhatsApp Reply</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>
