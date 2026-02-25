<?php

namespace App\Http\Controllers;

use App\Models\ClientReview;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class PublicInvoiceController extends Controller
{
    public function show(string $token): View
    {
        $invoice = Invoice::query()
            ->where('public_token', $token)
            ->with(['project.client'])
            ->firstOrFail();

        $project = $invoice->project;
        abort_unless($project instanceof Project, 404);

        $invoicePayload = $this->normalizeInvoicePayload($project, $invoice, (array) ($invoice->invoice_payload ?? []));
        $balance = max(0, (float) $invoice->amount - (float) $invoice->paid_amount);

        return view('pages.public-invoice', compact('invoice', 'project', 'invoicePayload', 'balance'));
    }

    public function quickPay(Request $request, string $token): RedirectResponse
    {
        $invoice = Invoice::query()
            ->where('public_token', $token)
            ->with(['project.client'])
            ->firstOrFail();

        $project = $invoice->project;
        abort_unless($project instanceof Project, 404);

        if (!$invoice->show_pay_button) {
            return redirect()
                ->route('invoice.public.show', ['token' => $invoice->public_token])
                ->withErrors(['payment' => 'Online payment is disabled for this invoice.']);
        }

        $remaining = max(0, (float) $invoice->amount - (float) $invoice->paid_amount);
        if ($remaining <= 0) {
            return redirect()
                ->route('invoice.public.show', ['token' => $invoice->public_token])
                ->with('success', 'This invoice is already fully paid.');
        }

        $reference = trim((string) $request->query('ref', ''));
        $checkoutUrl = $this->buildStripeCheckoutSessionUrl($project, $invoice, $remaining, $reference);

        if (!$checkoutUrl) {
            return redirect()
                ->route('invoice.public.show', ['token' => $invoice->public_token])
                ->withErrors(['payment' => 'Secure card payment is temporarily unavailable. Please try again shortly.']);
        }

        return redirect()->away($checkoutUrl);
    }

    public function pay(Request $request, string $token): RedirectResponse
    {
        $invoice = Invoice::query()
            ->where('public_token', $token)
            ->with(['project.client'])
            ->firstOrFail();

        $project = $invoice->project;
        abort_unless($project instanceof Project, 404);

        if (!$invoice->show_pay_button) {
            return back()->withErrors([
                'payment' => 'Online payment is disabled for this invoice. Please contact ARS Developer.',
            ]);
        }

        $remaining = max(0, (float) $invoice->amount - (float) $invoice->paid_amount);
        if ($remaining <= 0) {
            return back()->with('success', 'This invoice is already fully paid.');
        }

        $data = $request->validate([
            'amount' => ['nullable', 'numeric', 'min:0.01'],
            'reference' => ['nullable', 'string', 'max:120'],
        ]);

        $amount = isset($data['amount']) ? (float) $data['amount'] : $remaining;
        $amount = min($amount, $remaining);
        if ($amount <= 0) {
            return back()->withErrors([
                'payment' => 'Invalid payment amount.',
            ]);
        }

        $checkoutUrl = $this->buildStripeCheckoutSessionUrl($project, $invoice, $amount, (string) ($data['reference'] ?? ''));
        if (!$checkoutUrl) {
            return back()->withErrors([
                'payment' => 'Secure card payment is temporarily unavailable. Please try again in a moment.',
            ]);
        }

        return redirect()->away($checkoutUrl);
    }

    public function success(Request $request, string $token): RedirectResponse
    {
        $sessionId = trim((string) $request->query('session_id', ''));
        if ($sessionId === '') {
            return redirect()->route('invoice.public.show', ['token' => $token])
                ->withErrors(['payment' => 'Stripe session not found.']);
        }

        $invoice = Invoice::query()
            ->where('public_token', $token)
            ->with(['project.client'])
            ->firstOrFail();

        $project = $invoice->project;
        abort_unless($project instanceof Project, 404);

        try {
            $status = $this->syncStripeSessionPayment($project, $sessionId, $invoice);

            if ($status === 'paid') {
                return redirect()->route('invoice.public.show', ['token' => $token])
                    ->with('success', 'Payment confirmed. Receipt email has been sent.');
            }

            if ($status === 'already_paid') {
                return redirect()->route('invoice.public.show', ['token' => $token])
                    ->with('success', 'Payment already confirmed for this Stripe session.');
            }

            return redirect()->route('invoice.public.show', ['token' => $token])
                ->withErrors(['payment' => 'Payment is not confirmed yet. If charged, refresh in a few seconds.']);
        } catch (Throwable $e) {
            Log::error('Public invoice Stripe success callback failed.', [
                'invoice_id' => $invoice->id,
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('invoice.public.show', ['token' => $token])
                ->withErrors(['payment' => 'Could not verify Stripe payment. Please contact support with your payment receipt.']);
        }
    }

    private function hasStripeConfig(): bool
    {
        return trim((string) config('services.stripe.secret')) !== ''
            && trim((string) config('services.stripe.key')) !== '';
    }

    private function buildStripeCheckoutSessionUrl(Project $project, Invoice $invoice, float $paidAmount, string $reference = ''): ?string
    {
        if (!$this->hasStripeConfig()) {
            Log::warning('Stripe config is missing. Public invoice checkout session cannot be created.');
            return null;
        }

        $successUrl = route('invoice.public.pay.success', ['token' => $invoice->public_token]) . '?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = route('invoice.public.show', ['token' => $invoice->public_token]) . '#payment';
        $currency = strtolower((string) ($project->currency ?: 'GBP'));
        $unitAmount = (int) round($paidAmount * 100);

        $response = Http::asForm()
            ->withToken((string) config('services.stripe.secret'))
            ->post('https://api.stripe.com/v1/checkout/sessions', array_filter([
                'mode' => 'payment',
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'payment_method_types[0]' => 'card',
                'client_reference_id' => 'invoice_' . $invoice->id . '_project_' . $project->id,
                'customer_email' => $project->client?->email,
                'metadata[project_id]' => (string) $project->id,
                'metadata[invoice_id]' => (string) $invoice->id,
                'metadata[portal_token]' => (string) $project->portal_token,
                'metadata[invoice_public_token]' => (string) $invoice->public_token,
                'metadata[user_reference]' => trim($reference),
                'line_items[0][quantity]' => 1,
                'line_items[0][price_data][currency]' => $currency,
                'line_items[0][price_data][unit_amount]' => $unitAmount,
                'line_items[0][price_data][product_data][name]' => 'Invoice ' . $invoice->invoice_number . ' - ARSDeveloper',
            ], static fn ($value) => $value !== null && $value !== ''));

        if ($response->failed()) {
            Log::error('Stripe public invoice checkout session creation failed.', [
                'project_id' => $project->id,
                'invoice_id' => $invoice->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        }

        $checkoutUrl = (string) data_get($response->json(), 'url', '');
        if ($checkoutUrl === '') {
            Log::error('Stripe public invoice checkout session URL missing in response.', [
                'project_id' => $project->id,
                'invoice_id' => $invoice->id,
                'body' => $response->body(),
            ]);

            return null;
        }

        return $checkoutUrl;
    }

    private function syncStripeSessionPayment(Project $project, string $sessionId, Invoice $invoice): string
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
        $invoicePublicToken = trim((string) ($metadata['invoice_public_token'] ?? ''));
        $userReference = trim((string) ($metadata['user_reference'] ?? ''));

        if (
            $projectId !== (int) $project->id
            || $portalToken !== (string) $project->portal_token
            || $invoiceId !== (int) $invoice->id
            || $invoicePublicToken !== (string) $invoice->public_token
        ) {
            throw new \RuntimeException('Stripe metadata mismatch for public invoice payment.');
        }

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

            $notes = ['Paid by client via Stripe Checkout (public invoice link).'];
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

    private function normalizeInvoicePayload(Project $project, Invoice $invoice, array $payload): array
    {
        return [
            'headline' => trim((string) ($payload['headline'] ?? ('Invoice for ' . $project->title))),
            'intro' => trim((string) ($payload['intro'] ?? 'Please review this invoice and proceed with secure payment.')),
            'client_name' => trim((string) ($payload['client_name'] ?? ($project->client?->name ?? ''))),
            'client_company' => trim((string) ($payload['client_company'] ?? ($project->client?->company ?? ''))),
            'client_email' => trim((string) ($payload['client_email'] ?? ($project->client?->email ?? ''))),
            'client_phone' => trim((string) ($payload['client_phone'] ?? ($project->client?->phone ?? ''))),
            'project_summary' => trim((string) ($payload['project_summary'] ?? ((string) $project->description))),
            'scope_points' => collect((array) ($payload['scope_points'] ?? []))
                ->map(static fn ($line) => trim((string) $line))
                ->filter()
                ->values()
                ->all(),
            'terms' => trim((string) ($payload['terms'] ?? 'Payment confirms kickoff approval. Timeline starts from payment date.')),
            'extra_notes' => trim((string) ($payload['extra_notes'] ?? ((string) $invoice->notes))),
            'payment_label' => trim((string) ($payload['payment_label'] ?? 'Pay Securely with Stripe')),
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
            'invoiceUrl' => !empty($invoice->public_token)
                ? route('invoice.public.show', ['token' => $invoice->public_token])
                : null,
        ];

        try {
            Mail::send('emails.client-payment-received', $payload, function ($message) use ($project, $clientEmail, $invoice) {
                $message->to($clientEmail, $project->client?->name ?: 'Client')
                    ->subject('Payment Received - ' . $invoice->invoice_number);
            });
        } catch (Throwable $e) {
            Log::error('Failed to send payment receipt email from public invoice flow.', [
                'project_id' => $project->id,
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
            ]);
        }
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
            Log::error('Failed to send admin order alert email from public invoice flow.', [
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
                'review_token' => Str::random(56),
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
            Log::error('Failed to send review request from public invoice flow.', [
                'project_id' => $project->id,
                'invoice_id' => $invoice->id,
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
