<?php

namespace App\Http\Controllers;

use App\Mail\ContactAdminMail;
use App\Mail\ContactUserAcknowledgementMail;
use App\Models\BlockedContact;
use App\Models\Client;
use App\Models\Coupon;
use App\Models\CouponRedemption;
use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ContactFormController extends Controller
{
    public function __invoke(Request $request)
    {
        $payload = $this->normalizePayload($request);
        $meetingSlots = $this->meetingSlots();
        $expectsJson = $request->expectsJson() || $request->wantsJson();

        $isNewsletter = $payload['form_type'] === 'newsletter';
        $isMeeting = $payload['form_type'] === 'meeting';

        $validator = Validator::make($payload, [
            'name' => [$isNewsletter ? 'nullable' : 'required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:180'],
            'subject' => ['nullable', 'string', 'max:180'],
            'message' => [$isNewsletter ? 'nullable' : ($isMeeting ? 'nullable' : 'required'), 'string', 'max:5000'],
            'phone' => [$isMeeting ? 'required' : 'nullable', 'string', 'max:40'],
            'meeting_date' => [$isMeeting ? 'required' : 'nullable', 'date'],
            'meeting_slot' => array_filter([$isMeeting ? 'required' : 'nullable', 'string', 'max:120', $isMeeting ? ('in:' . implode(',', $meetingSlots)) : null]),
            'meeting_timezone' => [$isMeeting ? 'required' : 'nullable', 'string', 'max:80'],
            'project_type' => [$isMeeting ? 'required' : 'nullable', 'string', 'max:120'],
            'budget_range' => ['nullable', 'string', 'max:120'],
            'selected_plan_price' => ['nullable', 'numeric', 'min:0'],
            'start_order_payment' => ['nullable', 'boolean'],
            'company' => ['nullable', 'string', 'max:120'],
            'coupon_code' => ['nullable', 'string', 'max:40'],
            'coupon_discount' => ['nullable', 'numeric', 'min:0'],
            'final_quote_preview' => ['nullable', 'numeric', 'min:0'],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), $expectsJson);
        }

        if ($this->isBlockedContact($payload['email'], $payload['ip'])) {
            return $this->errorResponse('Your request cannot be processed right now.', $expectsJson);
        }

        if ($isNewsletter) {
            $payload['name'] = $payload['name'] !== '' ? $payload['name'] : 'Newsletter Subscriber';
            $payload['subject'] = $payload['subject'] !== '' ? $payload['subject'] : 'Newsletter Subscription Request';
            $payload['message'] = $payload['message'] !== '' ? $payload['message'] : 'Please add this email to newsletter updates.';
        }

        if ($isMeeting) {
            $payload['subject'] = $payload['subject'] !== '' ? $payload['subject'] : 'Meeting Booking Request';
            $payload['message'] = $payload['message'] !== '' ? $payload['message'] : 'Discovery call booking submitted from website.';
            if ($this->isSlotAlreadyBooked($payload['meeting_date'], $payload['meeting_slot'])) {
                return $this->errorResponse('This date or slot is not available. Please select another option.', $expectsJson);
            }
        }

        $lead = $this->storeLead($payload, $isNewsletter, $isMeeting);
        if ($isMeeting && $lead) {
            $payload = $this->hydrateMeetingPayload($payload, $lead, 'booked');
        }

        $wantsDirectOrderPayment = ($payload['form_type'] ?? '') === 'pricing_order'
            && filter_var($payload['start_order_payment'] ?? false, FILTER_VALIDATE_BOOLEAN)
            && is_numeric($payload['selected_plan_price'] ?? null);

        if ($wantsDirectOrderPayment) {
            $checkoutUrl = $this->buildDirectOrderCheckoutUrl($payload);
            if ($checkoutUrl) {
                if ($expectsJson) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Order started successfully. Redirecting to secure payment...',
                        'redirect_url' => $checkoutUrl,
                    ]);
                }

                return redirect()->away($checkoutUrl);
            }
        }

        $adminEmail = (string) config('contact.inbox_email', 'info@arsdeveloper.co.uk');
        $adminMailSent = false;
        $userMailSent = false;

        try {
            Mail::to($adminEmail)
                ->send((new ContactAdminMail($payload))->replyTo($payload['email'], $payload['name']));
            $adminMailSent = true;
        } catch (\Throwable $exception) {
            Log::error('Contact form admin email failed.', [
                'exception' => $exception->getMessage(),
                'email' => $payload['email'] ?? null,
                'subject' => $payload['subject'] ?? null,
                'form_type' => $payload['form_type'] ?? null,
            ]);
        }

        if ((bool) config('contact.auto_reply', true)) {
            try {
                Mail::to($payload['email'])->send(new ContactUserAcknowledgementMail($payload));
                $userMailSent = true;
            } catch (\Throwable $exception) {
                Log::error('Contact form user acknowledgement email failed.', [
                    'exception' => $exception->getMessage(),
                    'email' => $payload['email'] ?? null,
                    'subject' => $payload['subject'] ?? null,
                    'form_type' => $payload['form_type'] ?? null,
                ]);
            }
        } else {
            $userMailSent = true;
        }

        $formType = (string) ($payload['form_type'] ?? '');
        $successMessage = match ($formType) {
            'newsletter' => 'Thank you. You have been subscribed successfully.',
            'meeting' => 'Meeting booked successfully. Your slot is saved.',
            'pricing_order' => 'Order request received. We will send your invoice and onboarding steps shortly.',
            default => 'Thank you. Your request has been submitted successfully.',
        };

        if (!$adminMailSent || !$userMailSent) {
            $successMessage .= ' If confirmation email is delayed, our team still received your request.';
        }

        if ($expectsJson) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'mail_admin_sent' => $adminMailSent,
                'mail_user_sent' => $userMailSent,
                'redirect_url' => $isMeeting && $lead && $lead->meeting_token
                    ? route('meeting.confirmation', ['token' => $lead->meeting_token])
                    : null,
            ]);
        }

        if ($isMeeting && $lead && $lead->meeting_token) {
            return redirect()->route('meeting.confirmation', ['token' => $lead->meeting_token]);
        }

        return response($this->htmlMessage('success', $successMessage));
    }

    public function availability(Request $request)
    {
        $requestedDate = trim((string) $request->query('date', ''));
        $excludeToken = trim((string) $request->query('exclude_token', ''));
        $excludeLeadId = null;
        if ($excludeToken !== '' && Schema::hasTable('leads') && Schema::hasColumn('leads', 'meeting_token')) {
            $excludeLeadId = (int) (Lead::query()->where('meeting_token', $excludeToken)->value('id') ?: 0);
            if ($excludeLeadId <= 0) {
                $excludeLeadId = null;
            }
        }

        $slots = $this->meetingSlots();
        $bookedSlotMap = $this->bookedSlotMap($excludeLeadId);
        $fullyBookedDates = $this->fullyBookedDates($bookedSlotMap, $slots);

        $bookedSlots = [];
        $availableSlots = $slots;
        if ($requestedDate !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $requestedDate)) {
            $bookedSlots = array_values(array_keys($bookedSlotMap[$requestedDate] ?? []));
            $availableSlots = array_values(array_filter($slots, fn ($slot) => !in_array($slot, $bookedSlots, true)));
        }

        return response()->json([
            'date' => $requestedDate,
            'timezone' => (string) config('contact.meeting_timezone', 'Europe/London'),
            'slots' => $slots,
            'booked_slots' => $bookedSlots,
            'available_slots' => $availableSlots,
            'fully_booked_dates' => $fullyBookedDates,
        ]);
    }

    private function normalizePayload(Request $request): array
    {
        $formType = trim((string) $request->input('form_type', 'contact'));
        $name = $this->firstFilled($request, ['name', 'Name']);
        $email = $this->firstFilled($request, ['email', 'Email']);
        $subject = $this->firstFilled($request, ['subject', 'Phone', 'Inquiry About']) ?: ($formType === 'newsletter'
            ? 'Newsletter Subscription Request'
            : 'Website Inquiry');
        $message = $this->firstFilled($request, ['message', 'Write Details', 'comment']) ?: '';
        $phone = $this->firstFilled($request, ['phone', 'Phone']) ?: '';
        $company = $this->firstFilled($request, ['company', 'Company']) ?: '';
        $meetingDate = $this->firstFilled($request, ['meeting_date']) ?: '';
        $meetingSlot = $this->firstFilled($request, ['meeting_slot']) ?: '';
        $meetingTimezone = $this->firstFilled($request, ['meeting_timezone']) ?: ($formType === 'meeting'
            ? (string) config('contact.meeting_timezone', 'Europe/London')
            : '');
        $projectType = $this->firstFilled($request, ['project_type']) ?: '';
        $budgetRange = $this->firstFilled($request, ['budget_range']) ?: '';
        $couponCode = strtoupper(trim((string) ($this->firstFilled($request, ['coupon_code']) ?: '')));
        $couponDiscount = trim((string) ($this->firstFilled($request, ['coupon_discount']) ?: ''));
        $finalQuotePreview = trim((string) ($this->firstFilled($request, ['final_quote_preview']) ?: ''));

        return [
            'form_type' => $formType,
            'name' => trim((string) $name),
            'email' => trim((string) $email),
            'subject' => trim((string) $subject),
            'message' => trim((string) $message),
            'phone' => trim((string) $phone),
            'company' => trim((string) $company),
            'meeting_date' => trim((string) $meetingDate),
            'meeting_slot' => trim((string) $meetingSlot),
            'meeting_timezone' => trim((string) $meetingTimezone),
            'project_type' => trim((string) $projectType),
            'budget_range' => trim((string) $budgetRange),
            'selected_plan_price' => trim((string) ($this->firstFilled($request, ['selected_plan_price']) ?: '')),
            'start_order_payment' => filter_var($request->input('start_order_payment', false), FILTER_VALIDATE_BOOLEAN),
            'coupon_code' => $couponCode,
            'coupon_discount' => $couponDiscount,
            'final_quote_preview' => $finalQuotePreview,
            'ip' => (string) $request->ip(),
            'country' => $this->resolveCountry($request),
            'submitted_from' => (string) ($request->headers->get('referer') ?? url()->previous()),
            'submitted_at' => now()->toDateTimeString(),
            'user_agent' => (string) $request->userAgent(),
        ];
    }

    private function resolveCountry(Request $request): ?string
    {
        $headerCountry = trim((string) $request->headers->get('CF-IPCountry', ''));
        if ($headerCountry !== '' && strtoupper($headerCountry) !== 'XX') {
            return $headerCountry;
        }

        $serverCountry = trim((string) $request->server('GEOIP_COUNTRY_NAME', ''));
        if ($serverCountry !== '') {
            return $serverCountry;
        }

        return filter_var($request->ip(), FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
            ? 'Unknown'
            : 'Local';
    }

    private function firstFilled(Request $request, array $keys): ?string
    {
        foreach ($keys as $key) {
            $value = $request->input($key);
            if (is_string($value) && trim($value) !== '') {
                return $value;
            }
        }

        return null;
    }

    private function htmlMessage(string $type, string $message): string
    {
        $class = $type === 'success' ? 'contact-success-message' : 'contact-error-message';

        return '<p class="' . $class . '">' . e($message) . '</p>';
    }

    private function meetingSlots(): array
    {
        $slots = config('contact.meeting_slots', []);
        $slots = is_array($slots) ? $slots : [];
        $slots = array_values(array_filter(array_map(fn ($slot) => trim((string) $slot), $slots)));

        return $slots !== [] ? $slots : [
            '09:00 AM - 10:00 AM',
            '10:00 AM - 11:00 AM',
            '11:00 AM - 12:00 PM',
            '01:00 PM - 02:00 PM',
            '02:00 PM - 03:00 PM',
            '03:00 PM - 04:00 PM',
            '04:00 PM - 05:00 PM',
        ];
    }

    private function bookedSlotMap(?int $excludeLeadId = null): array
    {
        $map = [];

        if (Schema::hasTable('leads')) {
            $query = Lead::query()
                ->where('type', 'meeting')
                ->whereNotNull('meeting_date')
                ->whereNotNull('meeting_slot')
                ->whereNotIn('status', $this->meetingReleasedStatuses());

            if ($excludeLeadId !== null && $excludeLeadId > 0) {
                $query->where('id', '!=', $excludeLeadId);
            }

            $query
                ->get(['meeting_date', 'meeting_slot'])
                ->each(function (Lead $lead) use (&$map) {
                    $date = trim((string) $lead->meeting_date?->format('Y-m-d'));
                    $slot = trim((string) $lead->meeting_slot);
                    if ($date !== '' && $slot !== '') {
                        $map[$date][$slot] = true;
                    }
                });
        }

        return $map;
    }

    private function fullyBookedDates(array $bookedSlotMap, array $slots): array
    {
        $manualDates = config('contact.booked_dates', []);
        $full = array_fill_keys($manualDates, true);
        $slotCount = count($slots);

        foreach ($bookedSlotMap as $date => $slotSet) {
            if ($slotCount > 0 && count($slotSet) >= $slotCount) {
                $full[$date] = true;
            }
        }

        return array_values(array_keys($full));
    }

    private function isSlotAlreadyBooked(string $date, string $slot, ?int $exceptLeadId = null): bool
    {
        if ($date === '' || $slot === '') {
            return false;
        }

        if (Schema::hasTable('leads')) {
            $query = Lead::query()
                ->where('type', 'meeting')
                ->whereDate('meeting_date', $date)
                ->where('meeting_slot', $slot)
                ->whereNotIn('status', $this->meetingReleasedStatuses());

            if ($exceptLeadId !== null && $exceptLeadId > 0) {
                $query->where('id', '!=', $exceptLeadId);
            }

            $exists = $query->exists();

            if ($exists) {
                return true;
            }
        }

        return false;
    }

    private function storeLead(array $payload, bool $isNewsletter, bool $isMeeting): ?Lead
    {
        if (!Schema::hasTable('leads')) {
            return null;
        }

        $meetingTimezone = trim((string) ($payload['meeting_timezone'] ?: config('contact.meeting_timezone', 'Europe/London')));

        $attributes = [
            'type' => $isNewsletter ? 'newsletter' : ($isMeeting ? 'meeting' : 'contact'),
            'name' => $payload['name'],
            'email' => $payload['email'],
            'phone' => $payload['phone'],
            'company' => $payload['company'],
            'subject' => $payload['subject'],
            'message' => $payload['message'],
            'meeting_date' => $payload['meeting_date'] !== '' ? $payload['meeting_date'] : null,
            'meeting_slot' => $payload['meeting_slot'] !== '' ? $payload['meeting_slot'] : null,
            'project_type' => $payload['project_type'],
            'budget_range' => $payload['budget_range'],
            'status' => 'new',
            'ip' => $payload['ip'] ?? null,
            'submitted_from' => $payload['submitted_from'] ?? null,
            'user_agent' => $payload['user_agent'] ?? null,
        ];

        if (Schema::hasColumn('leads', 'country')) {
            $attributes['country'] = $payload['country'] ?? null;
        }

        if ($isMeeting && Schema::hasColumn('leads', 'meeting_token')) {
            $attributes['meeting_token'] = (string) Str::uuid();
        }

        if ($isMeeting && Schema::hasColumn('leads', 'meeting_timezone')) {
            $attributes['meeting_timezone'] = $meetingTimezone;
        }

        $resolvedCoupon = null;
        if (
            Schema::hasColumn('leads', 'coupon_code')
            || Schema::hasColumn('leads', 'coupon_discount')
            || Schema::hasColumn('leads', 'quote_final_preview')
            || Schema::hasColumn('leads', 'coupon_validated')
        ) {
            $resolvedCoupon = $this->resolveValidCoupon(
                (string) ($payload['coupon_code'] ?? ''),
                (string) ($payload['email'] ?? '')
            );
            $couponCode = $resolvedCoupon?->code ? strtoupper((string) $resolvedCoupon->code) : null;

            if (Schema::hasColumn('leads', 'coupon_code')) {
                $attributes['coupon_code'] = $couponCode;
            }
            if (Schema::hasColumn('leads', 'coupon_discount')) {
                $attributes['coupon_discount'] = is_numeric($payload['coupon_discount'] ?? null)
                    ? (float) $payload['coupon_discount']
                    : null;
            }
            if (Schema::hasColumn('leads', 'quote_final_preview')) {
                $attributes['quote_final_preview'] = is_numeric($payload['final_quote_preview'] ?? null)
                    ? (float) $payload['final_quote_preview']
                    : null;
            }
            if (Schema::hasColumn('leads', 'coupon_validated')) {
                $attributes['coupon_validated'] = $resolvedCoupon !== null;
            }
        }

        $lead = Lead::create($attributes);

        if ($resolvedCoupon && Schema::hasTable('coupon_redemptions')) {
            DB::transaction(function () use ($lead, $resolvedCoupon, $payload) {
                $email = strtolower(trim((string) ($payload['email'] ?? '')));
                $existing = CouponRedemption::query()
                    ->whereRaw('LOWER(email) = ?', [$email])
                    ->first();

                if ($existing) {
                    return;
                }

                CouponRedemption::query()->create([
                    'coupon_id' => (int) $resolvedCoupon->id,
                    'lead_id' => (int) $lead->id,
                    'email' => $email,
                    'discount_amount' => is_numeric($payload['coupon_discount'] ?? null) ? (float) $payload['coupon_discount'] : null,
                    'final_amount' => is_numeric($payload['final_quote_preview'] ?? null) ? (float) $payload['final_quote_preview'] : null,
                    'redeemed_at' => now(),
                ]);

                $resolvedCoupon->increment('used_count');
            });
        }

        return $lead;
    }

    private function resolveValidCoupon(string $couponCode, string $email): ?Coupon
    {
        $code = strtoupper(trim($couponCode));
        if ($code === '' || !Schema::hasTable('coupons')) {
            return null;
        }

        $normalizedEmail = strtolower(trim($email));
        if ($normalizedEmail !== '' && Schema::hasTable('coupon_redemptions')) {
            $alreadyRedeemed = CouponRedemption::query()
                ->whereRaw('LOWER(email) = ?', [$normalizedEmail])
                ->exists();
            if ($alreadyRedeemed) {
                return null;
            }
        }

        $coupon = Coupon::query()
            ->whereRaw('UPPER(code) = ?', [$code])
            ->where('is_active', true)
            ->first();

        if (!$coupon) {
            return null;
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return null;
        }

        if (!is_null($coupon->usage_limit) && (int) $coupon->used_count >= (int) $coupon->usage_limit) {
            return null;
        }

        return $coupon;
    }

    private function meetingReleasedStatuses(): array
    {
        return ['cancelled', 'meeting_completed', 'no_show', 'closed'];
    }

    private function hydrateMeetingPayload(array $payload, Lead $lead, string $event = 'booked'): array
    {
        $timezone = (string) ($lead->meeting_timezone ?: config('contact.meeting_timezone', 'Europe/London'));
        $meetingDate = $lead->meeting_date?->format('Y-m-d') ?: (string) ($payload['meeting_date'] ?? '');
        $meetingSlot = (string) ($lead->meeting_slot ?: ($payload['meeting_slot'] ?? ''));
        $range = $this->meetingRange($meetingDate, $meetingSlot, $timezone);

        $payload['form_type'] = 'meeting';
        $payload['meeting_event'] = $event;
        $payload['meeting_reference'] = 'MTG-' . str_pad((string) $lead->id, 6, '0', STR_PAD_LEFT);
        $payload['meeting_date'] = $meetingDate;
        $payload['meeting_slot'] = $meetingSlot;
        $payload['meeting_timezone'] = $timezone;
        $payload['mail_subject'] = 'Meeting booked successfully - ARSDeveloper';

        if (!empty($lead->meeting_token)) {
            $payload['meeting_manage_url'] = route('meeting.manage', ['token' => $lead->meeting_token]);
            $payload['meeting_cancel_url'] = route('meeting.cancel', ['token' => $lead->meeting_token]);
            $payload['meeting_confirmation_url'] = route('meeting.confirmation', ['token' => $lead->meeting_token]);
        }

        if ($range) {
            [$startAt, $endAt] = $range;
            $payload['meeting_date_label'] = $startAt->format('l, d M Y');
            $payload['meeting_time_label'] = $startAt->format('h:i A') . ' - ' . $endAt->format('h:i A');
        }

        return $payload;
    }

    private function meetingRange(string $date, string $slot, string $timezone): ?array
    {
        $baseTimezone = (string) config('contact.meeting_timezone', 'Europe/London');
        $parts = preg_split('/\s*-\s*/', str_replace('–', '-', trim($slot))) ?: [];
        if (count($parts) !== 2 || $date === '') {
            return null;
        }

        try {
            $start = Carbon::createFromFormat('Y-m-d g:i A', $date . ' ' . trim((string) $parts[0]), $baseTimezone);
            $end = Carbon::createFromFormat('Y-m-d g:i A', $date . ' ' . trim((string) $parts[1]), $baseTimezone);
        } catch (\Throwable $e) {
            return null;
        }

        if ($end->lessThanOrEqualTo($start)) {
            $end->addHour();
        }

        return [$start->copy()->setTimezone($timezone), $end->copy()->setTimezone($timezone)];
    }

    private function errorResponse(string $message, bool $expectsJson, int $status = 422)
    {
        if ($expectsJson) {
            return response()->json(['success' => false, 'message' => $message], $status);
        }

        return response($this->htmlMessage('error', $message));
    }

    private function isBlockedContact(string $email, string $ip): bool
    {
        if (!Schema::hasTable('blocked_contacts')) {
            return false;
        }

        return BlockedContact::query()
            ->where('is_active', true)
            ->where(function ($query) use ($email, $ip) {
                $query->where('email', $email);
                if ($ip !== '') {
                    $query->orWhere('ip', $ip);
                }
            })
            ->exists();
    }

    private function buildDirectOrderCheckoutUrl(array $payload): ?string
    {
        if (!$this->hasStripeConfig()) {
            return null;
        }

        $basePrice = is_numeric($payload['selected_plan_price'] ?? null) ? (float) $payload['selected_plan_price'] : 0.0;
        $finalPreview = is_numeric($payload['final_quote_preview'] ?? null) ? (float) $payload['final_quote_preview'] : 0.0;
        $payable = $finalPreview > 0 ? $finalPreview : $basePrice;
        $payable = round(max(0.0, $payable), 2);
        if ($payable <= 0) {
            return null;
        }

        $client = Client::query()->firstOrCreate(
            ['email' => strtolower((string) $payload['email'])],
            [
                'name' => $payload['name'] ?: 'Client',
                'phone' => $payload['phone'] ?: null,
                'company' => $payload['company'] ?: null,
                'country' => $payload['country'] ?? null,
            ]
        );

        $client->fill([
            'name' => $payload['name'] ?: $client->name,
            'phone' => $payload['phone'] ?: $client->phone,
            'company' => $payload['company'] ?: $client->company,
            'country' => $payload['country'] ?: $client->country,
        ])->save();

        $projectType = trim((string) ($payload['project_type'] ?? 'Website Project'));
        $project = Project::query()->create([
            'client_id' => (int) $client->id,
            'title' => $projectType !== '' ? $projectType : 'Website Project',
            'type' => $projectType,
            'status' => 'planning',
            'start_date' => now()->toDateString(),
            'delivery_date' => now()->addMonths(2)->toDateString(),
            'delivery_months' => 2,
            'budget_total' => $payable,
            'paid_total' => 0,
            'currency' => 'GBP',
            'portal_token' => Str::random(56),
            'description' => (string) ($payload['message'] ?? ''),
        ]);

        $project->milestones()->createMany([
            [
                'title' => 'Scope Review',
                'details' => 'Admin will verify submitted requirements and finalize execution scope.',
                'due_date' => now()->addDays(2)->toDateString(),
                'status' => 'in_progress',
                'sort_order' => 1,
            ],
            [
                'title' => 'Kickoff Setup',
                'details' => 'Project workspace and delivery timeline are prepared after payment confirmation.',
                'due_date' => now()->addDays(5)->toDateString(),
                'status' => 'pending',
                'sort_order' => 2,
            ],
            [
                'title' => 'Delivery Sprint',
                'details' => 'Implementation starts with milestone-based updates in client portal.',
                'due_date' => now()->addMonths(2)->toDateString(),
                'status' => 'pending',
                'sort_order' => 3,
            ],
        ]);

        if (trim((string) ($payload['message'] ?? '')) !== '') {
            $project->requirements()->create([
                'title' => 'Client Submitted Requirement',
                'description' => (string) $payload['message'],
                'source' => 'client',
                'status' => 'open',
            ]);
        }

        $invoiceData = [
            'invoice_number' => $this->generateInvoiceNumber(),
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->addDays(7)->toDateString(),
            'amount' => $payable,
            'paid_amount' => 0,
            'status' => 'unpaid',
            'notes' => $this->buildInvoiceNotes($payload, $basePrice, $payable),
        ];
        if (Schema::hasColumn('invoices', 'client_invoice_number')) {
            $invoiceData['client_invoice_number'] = $this->generateClientInvoiceNumber($project);
        }

        $invoice = $project->invoices()->create($invoiceData);

        return $this->createStripeCheckoutSession($project, $invoice, $payable, 'direct-order');
    }

    private function buildInvoiceNotes(array $payload, float $basePrice, float $payable): string
    {
        $notes = [];
        $notes[] = 'Direct order started from pricing flow.';
        if (!empty($payload['coupon_code'])) {
            $notes[] = 'Coupon: ' . strtoupper((string) $payload['coupon_code']);
        }
        if (is_numeric($payload['coupon_discount'] ?? null)) {
            $notes[] = 'Discount: GBP ' . number_format((float) $payload['coupon_discount'], 2);
        }
        if ($basePrice > 0 && $payable > 0) {
            $notes[] = 'Price: GBP ' . number_format($basePrice, 2) . ' | Payable: GBP ' . number_format($payable, 2);
        }
        return implode(' ', $notes);
    }

    private function generateInvoiceNumber(): string
    {
        $year = now()->format('Y');
        $counter = (int) (Invoice::query()->count() + 1);
        do {
            $candidate = 'INV-' . $year . '-' . str_pad((string) $counter, 4, '0', STR_PAD_LEFT);
            $exists = Invoice::query()->where('invoice_number', $candidate)->exists();
            $counter++;
        } while ($exists);
        return $candidate;
    }

    private function generateClientInvoiceNumber(Project $project): string
    {
        if (!Schema::hasColumn('invoices', 'client_invoice_number')) {
            return '';
        }

        $year = now()->format('Y');
        $counter = max(1, (int) Invoice::query()->where('project_id', $project->id)->count() + 1);
        do {
            $candidate = 'CL-' . (int) $project->client_id . '-' . $year . '-' . str_pad((string) $counter, 4, '0', STR_PAD_LEFT);
            $exists = Invoice::query()->where('client_invoice_number', $candidate)->exists();
            $counter++;
        } while ($exists);
        return $candidate;
    }

    private function hasStripeConfig(): bool
    {
        return trim((string) config('services.stripe.secret')) !== ''
            && trim((string) config('services.stripe.key')) !== '';
    }

    private function createStripeCheckoutSession(Project $project, Invoice $invoice, float $amount, string $reference = ''): ?string
    {
        $successUrl = route('client.portal.pay.success', ['token' => $project->portal_token]) . '?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = route('client.portal', ['token' => $project->portal_token]) . '#invoices';
        $unitAmount = (int) round($amount * 100);

        $payload = [
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'payment_method_types[0]' => 'card',
            'client_reference_id' => 'project_' . $project->id . '_invoice_' . $invoice->id,
            'customer_email' => (string) ($project->client?->email ?? ''),
            'metadata[project_id]' => (string) $project->id,
            'metadata[invoice_id]' => (string) $invoice->id,
            'metadata[portal_token]' => (string) $project->portal_token,
            'metadata[user_reference]' => trim($reference),
            'line_items[0][quantity]' => 1,
            'line_items[0][price_data][currency]' => strtolower((string) ($project->currency ?: 'GBP')),
            'line_items[0][price_data][unit_amount]' => $unitAmount,
            'line_items[0][price_data][product_data][name]' => 'Invoice ' . $invoice->invoice_number . ' - ARSDeveloper',
        ];

        $response = Http::asForm()
            ->withToken((string) config('services.stripe.secret'))
            ->post('https://api.stripe.com/v1/checkout/sessions', array_filter(
                $payload,
                static fn ($value) => $value !== null && $value !== ''
            ));

        if ($response->failed()) {
            Log::error('Direct order stripe checkout session failed.', [
                'project_id' => $project->id,
                'invoice_id' => $invoice->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        }

        $url = (string) data_get($response->json(), 'url', '');
        return $url !== '' ? $url : null;
    }
}
