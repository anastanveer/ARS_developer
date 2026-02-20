<?php

namespace App\Http\Controllers;

use App\Models\ClientReview;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class ClientPortalController extends Controller
{
    public function show(string $token): View
    {
        $project = Project::query()
            ->where('portal_token', $token)
            ->with(['client', 'milestones', 'requirements', 'invoices', 'payments'])
            ->firstOrFail();

        $balance = (float) $project->budget_total - (float) $project->paid_total;
        $timeline = $this->buildTimeline($project);

        return view('pages.client-portal', compact('project', 'balance', 'timeline'));
    }

    public function addRequirement(Request $request, string $token): RedirectResponse
    {
        $project = Project::query()->where('portal_token', $token)->firstOrFail();

        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string'],
        ]);

        $project->requirements()->create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'source' => 'client',
            'status' => 'open',
        ]);

        return back()->with('success', 'Requirement submitted successfully.');
    }

    public function payInvoice(Request $request, string $token): RedirectResponse
    {
        $project = Project::query()
            ->where('portal_token', $token)
            ->with(['client'])
            ->firstOrFail();

        $data = $request->validate([
            'invoice_id' => ['required', 'integer'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'method' => ['nullable', 'string', 'max:60'],
            'reference' => ['nullable', 'string', 'max:120'],
        ]);

        $invoice = Invoice::query()
            ->where('project_id', $project->id)
            ->findOrFail((int) $data['invoice_id']);

        $remaining = max(0, (float) $invoice->amount - (float) $invoice->paid_amount);
        $paidAmount = min((float) $data['amount'], $remaining);

        if ($paidAmount <= 0) {
            return back()->with('success', 'This invoice is already fully paid.');
        }

        $method = trim((string) ($data['method'] ?? 'Portal Payment'));

        if ($this->shouldUseStripe($method)) {
            $checkoutUrl = $this->buildStripeCheckoutSessionUrl($project, $invoice, $paidAmount, (string) ($data['reference'] ?? ''));
            if (!$checkoutUrl) {
                return back()->withErrors([
                    'payment' => 'Secure card payment is temporarily unavailable. Please try again in a moment.',
                ]);
            }

            return redirect()->away($checkoutUrl);
        }

        $payment = $project->payments()->create([
            'invoice_id' => $invoice->id,
            'amount' => $paidAmount,
            'payment_date' => now()->toDateString(),
            'method' => $method !== '' ? $method : 'Portal Payment',
            'reference' => $data['reference'] ?: null,
            'gateway_payment_id' => null,
            'notes' => 'Paid by client via portal.',
        ]);

        $invoiceWasPaidBefore = (string) $invoice->status === 'paid';
        $invoice->paid_amount = (float) $invoice->paid_amount + $paidAmount;
        $invoice->status = $invoice->paid_amount >= (float) $invoice->amount ? 'paid' : 'partially_paid';
        $invoice->save();

        $project->paid_total = (float) $project->payments()->sum('amount');
        $project->save();

        $this->sendPaymentEmail($project, $invoice, $payment);
        $this->sendAdminOrderAlertEmail($project, $invoice, $payment);
        if (!$invoiceWasPaidBefore && (string) $invoice->status === 'paid') {
            $this->sendReviewRequestEmail($project, $invoice, $payment);
        }

        return back()->with('success', 'Payment recorded successfully. Receipt email sent.');
    }

    public function handleStripeSuccess(Request $request, string $token): RedirectResponse
    {
        $sessionId = trim((string) $request->query('session_id', ''));
        if ($sessionId === '') {
            return redirect()->route('client.portal', $token)
                ->withErrors(['payment' => 'Stripe session not found.']);
        }

        $project = Project::query()
            ->where('portal_token', $token)
            ->with(['client'])
            ->firstOrFail();

        try {
            $status = $this->syncStripeSessionPayment($project, $sessionId);

            if ($status === 'paid') {
                return redirect()->route('client.portal', $token)
                    ->with('success', 'Payment confirmed via Stripe. Receipt email sent.');
            }

            if ($status === 'already_paid') {
                return redirect()->route('client.portal', $token)
                    ->with('success', 'Payment already confirmed for this Stripe session.');
            }

            return redirect()->route('client.portal', $token)
                ->withErrors(['payment' => 'Payment is not confirmed yet. If charged, refresh in a few seconds.']);
        } catch (Throwable $e) {
            Log::error('Stripe success callback failed.', [
                'token' => $token,
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('client.portal', $token)
                ->withErrors(['payment' => 'Could not verify Stripe payment. Please contact support with your payment receipt.']);
        }
    }

    public function stripeWebhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $signatureHeader = (string) $request->header('Stripe-Signature', '');
        $secret = (string) config('services.stripe.webhook_secret');

        if (!$this->verifyStripeSignature($payload, $signatureHeader, $secret)) {
            return response()->json(['ok' => false, 'message' => 'Invalid Stripe signature.'], 400);
        }

        $event = json_decode($payload, true);
        if (!is_array($event)) {
            return response()->json(['ok' => false, 'message' => 'Invalid event payload.'], 400);
        }

        $eventType = (string) ($event['type'] ?? '');
        if (in_array($eventType, ['checkout.session.completed', 'checkout.session.async_payment_succeeded'], true)) {
            $sessionId = trim((string) data_get($event, 'data.object.id', ''));
            $token = trim((string) data_get($event, 'data.object.metadata.portal_token', ''));

            if ($sessionId !== '' && $token !== '') {
                $project = Project::query()
                    ->where('portal_token', $token)
                    ->with(['client'])
                    ->first();

                if ($project) {
                    try {
                        $this->syncStripeSessionPayment($project, $sessionId);
                    } catch (Throwable $e) {
                        Log::error('Stripe webhook sync failed.', [
                            'event_type' => $eventType,
                            'session_id' => $sessionId,
                            'token' => $token,
                            'error' => $e->getMessage(),
                        ]);
                    }
                }
            }
        }

        return response()->json(['ok' => true]);
    }

    private function buildTimeline(Project $project): array
    {
        if (empty($project->start_date) || empty($project->delivery_date)) {
            return [
                'total_days' => null,
                'elapsed_days' => null,
                'remaining_days' => null,
                'progress_percent' => null,
            ];
        }

        $startDate = $project->start_date->copy()->startOfDay();
        $deliveryDate = $project->delivery_date->copy()->startOfDay();
        $today = now()->startOfDay();

        $total = max(1, (int) $startDate->diffInDays($deliveryDate));
        $elapsedRaw = (int) $startDate->diffInDays($today, false);
        $elapsed = min($total, max(0, $elapsedRaw));
        $remaining = max(0, $total - $elapsed);
        $percent = (int) round(($elapsed / $total) * 100);

        return [
            'total_days' => $total,
            'elapsed_days' => $elapsed,
            'remaining_days' => $remaining,
            'progress_percent' => $percent,
        ];
    }

    private function sendPaymentEmail(Project $project, Invoice $invoice, Payment $payment): void
    {
        $clientEmail = $project->client?->email;
        if (empty($clientEmail)) {
            return;
        }

        $payload = [
            'project' => $project,
            'invoice' => $invoice,
            'payment' => $payment,
            'portalUrl' => route('client.portal', $project->portal_token),
        ];

        try {
            Mail::send('emails.client-payment-received', $payload, function ($message) use ($project, $clientEmail, $invoice) {
                $message->to($clientEmail, $project->client?->name ?: 'Client')
                    ->subject('Payment Received - '.$invoice->invoice_number);
            });
        } catch (Throwable $e) {
            Log::error('Failed to send payment receipt email from client portal.', [
                'project_id' => $project->id,
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function shouldUseStripe(string $method): bool
    {
        return in_array(strtolower(trim($method)), [
            'portal payment',
            'card',
            'stripe',
            'stripe card',
        ], true);
    }

    private function hasStripeConfig(): bool
    {
        return trim((string) config('services.stripe.secret')) !== ''
            && trim((string) config('services.stripe.key')) !== '';
    }

    private function buildStripeCheckoutSessionUrl(Project $project, Invoice $invoice, float $paidAmount, string $reference = ''): ?string
    {
        if (!$this->hasStripeConfig()) {
            Log::warning('Stripe config is missing. Checkout session cannot be created.');
            return null;
        }

        $successUrl = route('client.portal.pay.success', ['token' => $project->portal_token]) . '?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = route('client.portal', ['token' => $project->portal_token]) . '#invoices';
        $currency = strtolower((string) ($project->currency ?: 'GBP'));
        $unitAmount = (int) round($paidAmount * 100);

        $response = Http::asForm()
            ->withToken((string) config('services.stripe.secret'))
            ->post('https://api.stripe.com/v1/checkout/sessions', array_filter([
                'mode' => 'payment',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'payment_method_types[0]' => 'card',
                'client_reference_id' => 'project_' . $project->id . '_invoice_' . $invoice->id,
                'customer_email' => $project->client?->email,
                'metadata[project_id]' => (string) $project->id,
                'metadata[invoice_id]' => (string) $invoice->id,
                'metadata[portal_token]' => (string) $project->portal_token,
                'metadata[user_reference]' => trim($reference),
                'line_items[0][quantity]' => 1,
                'line_items[0][price_data][currency]' => $currency,
                'line_items[0][price_data][unit_amount]' => $unitAmount,
                'line_items[0][price_data][product_data][name]' => 'Invoice ' . $invoice->invoice_number . ' - ARSDeveloper',
            ], static fn ($value) => $value !== null && $value !== ''));

        if ($response->failed()) {
            Log::error('Stripe checkout session creation failed.', [
                'project_id' => $project->id,
                'invoice_id' => $invoice->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return null;
        }

        $checkoutUrl = (string) data_get($response->json(), 'url', '');
        if ($checkoutUrl === '') {
            Log::error('Stripe checkout session URL missing in response.', [
                'project_id' => $project->id,
                'invoice_id' => $invoice->id,
                'body' => $response->body(),
            ]);
            return null;
        }

        return $checkoutUrl;
    }

    private function syncStripeSessionPayment(Project $project, string $sessionId): string
    {
        $existing = Payment::query()
            ->where('project_id', $project->id)
            ->where('reference', $sessionId)
            ->first();

        if ($existing) {
            return 'already_paid';
        }

        $session = $this->fetchStripeCheckoutSession($sessionId);
        $paymentStatus = trim((string) data_get($session, 'payment_status', ''));
        if ($paymentStatus !== 'paid') {
            return 'pending';
        }

        $metadata = (array) data_get($session, 'metadata', []);
        $projectId = (int) ($metadata['project_id'] ?? 0);
        $invoiceId = (int) ($metadata['invoice_id'] ?? 0);
        $portalToken = trim((string) ($metadata['portal_token'] ?? ''));
        $userReference = trim((string) ($metadata['user_reference'] ?? ''));

        if ($projectId !== (int) $project->id || $portalToken !== (string) $project->portal_token || $invoiceId <= 0) {
            throw new \RuntimeException('Stripe metadata mismatch for project or invoice.');
        }

        $invoice = Invoice::query()
            ->where('project_id', $project->id)
            ->findOrFail($invoiceId);

        $amountTotal = (int) data_get($session, 'amount_total', 0);
        $amountFromStripe = round($amountTotal / 100, 2);
        if ($amountFromStripe <= 0) {
            throw new \RuntimeException('Stripe returned zero payment amount.');
        }

        $paymentIntent = trim((string) data_get($session, 'payment_intent.id', data_get($session, 'payment_intent', '')));
        $createdPayment = null;

        DB::transaction(function () use ($project, $invoice, $sessionId, $amountFromStripe, $paymentIntent, $userReference, &$createdPayment): void {
            $alreadyExists = Payment::query()
                ->where('project_id', $project->id)
                ->where('reference', $sessionId)
                ->exists();

            if ($alreadyExists) {
                return;
            }

            $lockedInvoice = Invoice::query()
                ->where('project_id', $project->id)
                ->lockForUpdate()
                ->findOrFail($invoice->id);

            $remaining = max(0, (float) $lockedInvoice->amount - (float) $lockedInvoice->paid_amount);
            $recordAmount = min($amountFromStripe, $remaining);
            if ($recordAmount <= 0) {
                return;
            }

            $notes = ['Paid by client via Stripe Checkout.'];
            if ($paymentIntent !== '') {
                $notes[] = 'PaymentIntent: ' . $paymentIntent;
            }
            if ($userReference !== '') {
                $notes[] = 'Client Ref: ' . $userReference;
            }

            $createdPayment = $project->payments()->create([
                'invoice_id' => $lockedInvoice->id,
                'amount' => $recordAmount,
                'payment_date' => now()->toDateString(),
                'method' => 'Stripe Card',
                'reference' => $sessionId,
                'gateway_payment_id' => $paymentIntent !== '' ? $paymentIntent : null,
                'notes' => implode(' ', $notes),
            ]);

            $invoiceWasPaidBefore = (string) $lockedInvoice->status === 'paid';
            $lockedInvoice->paid_amount = (float) $lockedInvoice->paid_amount + $recordAmount;
            $lockedInvoice->status = $lockedInvoice->paid_amount >= (float) $lockedInvoice->amount ? 'paid' : 'partially_paid';
            $lockedInvoice->save();

            $project->paid_total = (float) $project->payments()->sum('amount');
            $project->save();

            if (!$invoiceWasPaidBefore && (string) $lockedInvoice->status === 'paid') {
                $createdPayment->setAttribute('invoice_now_paid', true);
            }
        });

        if ($createdPayment instanceof Payment) {
            $invoice->refresh();
            $project->refresh();
            $project->loadMissing('client');
            $this->sendPaymentEmail($project, $invoice, $createdPayment);
            $this->sendAdminOrderAlertEmail($project, $invoice, $createdPayment);
            if ((bool) $createdPayment->getAttribute('invoice_now_paid')) {
                $this->sendReviewRequestEmail($project, $invoice, $createdPayment);
            }
            return 'paid';
        }

        return 'already_paid';
    }

    private function fetchStripeCheckoutSession(string $sessionId): array
    {
        if (!$this->hasStripeConfig()) {
            throw new \RuntimeException('Stripe configuration is missing.');
        }

        $response = Http::withToken((string) config('services.stripe.secret'))
            ->get('https://api.stripe.com/v1/checkout/sessions/' . urlencode($sessionId), [
                'expand' => ['payment_intent'],
            ]);

        if ($response->failed()) {
            throw new \RuntimeException('Stripe session lookup failed: ' . $response->status());
        }

        $session = $response->json();
        if (!is_array($session)) {
            throw new \RuntimeException('Invalid Stripe session response.');
        }

        return $session;
    }

    private function verifyStripeSignature(string $payload, string $signatureHeader, string $secret, int $tolerance = 300): bool
    {
        if (trim($secret) === '' || trim($signatureHeader) === '') {
            return false;
        }

        $parts = [];
        foreach (explode(',', $signatureHeader) as $segment) {
            [$key, $value] = array_pad(explode('=', trim($segment), 2), 2, null);
            if ($key !== null && $value !== null) {
                $parts[$key][] = $value;
            }
        }

        $timestamp = (int) (($parts['t'][0] ?? 0));
        $signatures = $parts['v1'] ?? [];

        if ($timestamp <= 0 || empty($signatures)) {
            return false;
        }

        if (abs(time() - $timestamp) > $tolerance) {
            return false;
        }

        $signedPayload = $timestamp . '.' . $payload;
        $expectedSignature = hash_hmac('sha256', $signedPayload, $secret);

        foreach ($signatures as $signature) {
            if (hash_equals($expectedSignature, $signature)) {
                return true;
            }
        }

        return false;
    }

    private function sendAdminOrderAlertEmail(Project $project, Invoice $invoice, Payment $payment): void
    {
        $adminEmail = trim((string) config('contact.inbox_email', 'info@arsdeveloper.co.uk'));
        if ($adminEmail === '') {
            return;
        }

        $payload = [
            'project' => $project,
            'invoice' => $invoice,
            'payment' => $payment,
        ];

        try {
            Mail::send('emails.admin-order-alert', $payload, function ($message) use ($adminEmail, $invoice) {
                $message->to($adminEmail)
                    ->subject('Order Payment Received - ' . $invoice->invoice_number);
            });
        } catch (Throwable $e) {
            Log::error('Failed to send admin order alert email from client portal.', [
                'project_id' => $project->id,
                'invoice_id' => $invoice->id,
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function sendReviewRequestEmail(Project $project, Invoice $invoice, Payment $payment): void
    {
        $clientEmail = trim((string) ($project->client?->email ?? ''));
        if ($clientEmail === '') {
            return;
        }

        $review = ClientReview::query()->firstOrCreate(
            ['invoice_id' => $invoice->id],
            [
                'client_id' => $project->client_id,
                'project_id' => $project->id,
                'payment_id' => $payment->id,
                'review_token' => \Illuminate\Support\Str::random(56),
                'reviewer_name' => (string) ($project->client?->name ?? ''),
                'reviewer_email' => $clientEmail,
                'company_name' => (string) ($project->client?->company ?? ''),
            ]
        );

        if ($review->email_sent_at) {
            return;
        }

        $payload = [
            'project' => $project,
            'invoice' => $invoice,
            'payment' => $payment,
            'reviewUrl' => route('review.show', ['token' => $review->review_token]),
            'portalUrl' => route('client.portal', ['token' => $project->portal_token]),
        ];

        try {
            Mail::send('emails.client-thank-you-review-request', $payload, function ($message) use ($clientEmail, $project, $invoice) {
                $message->to($clientEmail, $project->client?->name ?: 'Client')
                    ->subject('Thank You - Invoice Paid (' . $invoice->invoice_number . ')');
            });

            $review->email_sent_at = now();
            $review->save();
        } catch (Throwable $e) {
            Log::error('Failed to send review request from client portal flow.', [
                'project_id' => $project->id,
                'invoice_id' => $invoice->id,
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
