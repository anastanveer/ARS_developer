<div style="font-family:Arial,sans-serif;line-height:1.7;color:#173153;max-width:640px;margin:0 auto;padding:24px;">
    <h2 style="margin:0 0 12px;color:#10356b;">Reply from ARS Developer Ltd</h2>
    <p style="margin:0 0 16px;">Hi {{ $conversation->name ?: 'there' }},</p>
    <p style="margin:0 0 16px;">Our team replied to your website chat:</p>
    <div style="padding:16px 18px;border-radius:16px;background:#edf6ff;border:1px solid #dbe7f9;">
        {{ $message->body }}
    </div>
    <p style="margin:16px 0 0;">You can continue the conversation on the website chat or reply to this email if needed.</p>
</div>
