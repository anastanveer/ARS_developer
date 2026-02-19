<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\EmailLog;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LeadEmailController extends Controller
{
    public function send(Request $request, Lead $lead): RedirectResponse
    {
        $data = $request->validate([
            'kind' => ['required', 'in:follow_up,coupon,custom'],
            'subject' => ['required', 'string', 'max:200'],
            'body' => ['nullable', 'string'],
            'coupon_id' => ['nullable', 'integer', 'exists:coupons,id'],
        ]);

        $body = trim((string) ($data['body'] ?? ''));

        if ($data['kind'] === 'coupon' && !empty($data['coupon_id'])) {
            $coupon = Coupon::find($data['coupon_id']);
            if ($coupon) {
                $discountLabel = $coupon->discount_type === 'percent'
                    ? rtrim(rtrim((string) $coupon->discount_value, '0'), '.') . '%'
                    : $coupon->currency . ' ' . rtrim(rtrim((string) $coupon->discount_value, '0'), '.');

                $couponText = "\n\nCoupon Code: {$coupon->code}\nDiscount: {$discountLabel}";
                if ($coupon->expires_at) {
                    $couponText .= "\nExpiry: " . $coupon->expires_at->format('d M Y H:i');
                }
                $couponText .= "\nUse On: https://arsdeveloper.co.uk/pricing";
                $couponText .= "\nRule: One coupon redemption per client email.";
                $body .= $couponText;
            }
        }

        $signature = "\n\nDirector"
            . "\n" . (string) config('company.legal_name')
            . "\nCompany No: " . (string) config('company.company_number')
            . "\nRegistered in " . (string) config('company.registered_in');

        if (!str_contains($body, (string) config('company.company_number'))) {
            $body .= $signature;
        }

        $log = EmailLog::create([
            'lead_id' => $lead->id,
            'kind' => $data['kind'],
            'to_email' => $lead->email,
            'subject' => $data['subject'],
            'body' => $body,
            'status' => 'queued',
            'sent_by' => (string) session('admin_email', 'admin'),
        ]);

        try {
            $html = nl2br(e($body !== '' ? $body : 'Hello, thank you for your interest. We will contact you shortly.'));
            Mail::send([], [], function ($message) use ($lead, $data, $html) {
                $message->to($lead->email, $lead->name ?: 'Client')
                    ->subject($data['subject'])
                    ->html($html);
            });

            $log->update([
                'status' => 'sent',
                'sent_at' => now(),
            ]);

            $lead->update([
                'last_followup_at' => now(),
                'status' => 'contacted',
            ]);

            return back()->with('success', 'Email sent successfully.');
        } catch (\Throwable $exception) {
            $log->update([
                'status' => 'failed',
                'error_message' => $exception->getMessage(),
            ]);

            return back()->with('error', 'Email could not be sent. Please check SMTP settings.');
        }
    }
}
