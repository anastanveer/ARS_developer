<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientReview;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\ProjectRequirement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::query()->with('client')->latest();

        if ($request->filled('q')) {
            $term = trim((string) $request->input('q'));
            $query->where(function ($q) use ($term) {
                $q->where('title', 'like', "%{$term}%")
                    ->orWhere('type', 'like', "%{$term}%")
                    ->orWhereHas('client', function ($clientQ) use ($term) {
                        $clientQ->where('name', 'like', "%{$term}%")
                            ->orWhere('company', 'like', "%{$term}%")
                            ->orWhere('email', 'like', "%{$term}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }

        $projects = $query->paginate(15)->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    public function create(): View
    {
        $clients = Client::query()->orderBy('name')->get();

        return view('admin.projects.create', compact('clients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedProjectData($request);
        if (empty($data['currency'])) {
            $data['currency'] = $this->resolveCurrencyByClientId((int) $data['client_id']);
        }
        $data['portal_token'] = Str::random(48);
        $data['paid_total'] = 0;
        $data['delivery_date'] = $this->calculateDeliveryDate($data['start_date'] ?? null, (int) ($data['delivery_months'] ?? 3));

        $project = Project::create($data);

        return redirect()->route('admin.projects.show', $project)->with('success', 'Project created. Add milestones and invoices below.');
    }

    public function show(Project $project): View
    {
        $project->load(['client', 'milestones', 'requirements', 'invoices', 'payments.invoice']);

        $balance = (float) $project->budget_total - (float) $project->paid_total;

        return view('admin.projects.show', compact('project', 'balance'));
    }

    public function edit(Project $project): View
    {
        $clients = Client::query()->orderBy('name')->get();

        return view('admin.projects.edit', compact('project', 'clients'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $this->validatedProjectData($request);
        if (empty($data['currency'])) {
            $data['currency'] = $this->resolveCurrencyByClientId((int) $data['client_id']);
        }

        if ($request->boolean('recalculate_delivery')) {
            $data['delivery_date'] = $this->calculateDeliveryDate($data['start_date'] ?? null, (int) ($data['delivery_months'] ?? 3));
        }

        $project->update($data);

        return redirect()->route('admin.projects.show', $project)->with('success', 'Project updated.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Project deleted.');
    }

    public function storeMilestone(Request $request, Project $project): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'details' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
            'status' => ['required', 'in:pending,in_progress,done'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? ((int) $project->milestones()->max('sort_order') + 1);
        $project->milestones()->create($data);

        return back()->with('success', 'Milestone added.');
    }

    public function updateMilestone(Request $request, Project $project, ProjectMilestone $milestone): RedirectResponse
    {
        abort_unless($milestone->project_id === $project->id, 404);

        $data = $request->validate([
            'status' => ['required', 'in:pending,in_progress,done'],
        ]);

        $milestone->update($data);

        return back()->with('success', 'Milestone status updated.');
    }

    public function storeRequirement(Request $request, Project $project): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'description' => ['nullable', 'string'],
            'source' => ['required', 'in:admin,client'],
            'status' => ['required', 'in:open,in_progress,done,blocked'],
        ]);

        $project->requirements()->create($data);

        return back()->with('success', 'Requirement added.');
    }

    public function updateRequirement(Request $request, Project $project, ProjectRequirement $requirement): RedirectResponse
    {
        abort_unless($requirement->project_id === $project->id, 404);

        $data = $request->validate([
            'status' => ['required', 'in:open,in_progress,done,blocked'],
        ]);

        $requirement->update($data);

        return back()->with('success', 'Requirement status updated.');
    }

    public function storeInvoice(Request $request, Project $project): RedirectResponse
    {
        $data = $request->validate([
            'invoice_number' => ['nullable', 'string', 'max:60', 'unique:invoices,invoice_number'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:unpaid,partially_paid,pending,paid,overdue,failed,cancelled,received,late,successful'],
            'notes' => ['nullable', 'string'],
            'send_to_email' => ['nullable', 'email', 'max:180'],
            'send_link_mode' => ['nullable', 'in:invoice,payment,pdf,portal,none'],
            'send_subject' => ['nullable', 'string', 'max:170'],
        ]);

        $statusRaw = (string) ($data['status'] ?? 'unpaid');
        $data['status'] = match ($statusRaw) {
            'received', 'successful' => 'paid',
            'late' => 'overdue',
            default => $statusRaw,
        };

        $invoiceNumber = trim((string) ($data['invoice_number'] ?? ''));
        if ($invoiceNumber === '') {
            $data['invoice_number'] = $this->generateInvoiceNumber();
        }

        $data['paid_amount'] = 0;
        $data['client_invoice_number'] = $this->generateClientInvoiceNumber($project);
        $data['public_token'] = Str::random(56);
        $data['show_pay_button'] = true;
        $data['invoice_payload'] = ['source' => 'admin_project'];
        $invoice = $project->invoices()->create($data);
        $project->load('client');

        $sendMode = (string) ($data['send_link_mode'] ?? 'invoice');
        if ($sendMode === '') {
            $sendMode = 'invoice';
        }

        $targetEmail = trim((string) ($data['send_to_email'] ?? ''));
        if ($targetEmail === '') {
            $targetEmail = trim((string) ($project->client?->email ?? ''));
        }

        $subject = trim((string) ($data['send_subject'] ?? ''));
        if ($subject === '') {
            $subject = $this->defaultInvoiceSubjectForMode($sendMode, $project, $invoice);
        }

        if ($sendMode !== 'none' && $targetEmail !== '') {
            try {
                $this->sendInvoiceLinkEmail($project, $invoice, $targetEmail, $subject, $sendMode);
                return back()
                    ->with('success', 'Invoice created and sent successfully.')
                    ->with('direct_invoice_url', route('invoice.public.show', ['token' => $invoice->public_token]))
                    ->with('direct_payment_url', route('invoice.public.pay-now', ['token' => $invoice->public_token]))
                    ->with('direct_pdf_url', route('invoice.public.show', ['token' => $invoice->public_token]) . '?print=1')
                    ->with('direct_portal_url', route('client.portal', ['token' => $project->portal_token]));
            } catch (\Throwable $e) {
                return back()->withErrors([
                    'invoice_send' => 'Invoice created but email failed. You can resend from Invoice Studio / Invoice Center.',
                ]);
            }
        }

        return back()
            ->with('success', 'Invoice created successfully. Use Invoice Studio to send invoice, payment or PDF link.')
            ->with('direct_invoice_url', route('invoice.public.show', ['token' => $invoice->public_token]))
            ->with('direct_payment_url', route('invoice.public.pay-now', ['token' => $invoice->public_token]))
            ->with('direct_pdf_url', route('invoice.public.show', ['token' => $invoice->public_token]) . '?print=1')
            ->with('direct_portal_url', route('client.portal', ['token' => $project->portal_token]));
    }

    public function storePayment(Request $request, Project $project): RedirectResponse
    {
        $data = $request->validate([
            'invoice_id' => ['nullable', 'integer', 'exists:invoices,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'payment_date' => ['required', 'date'],
            'method' => ['nullable', 'string', 'max:60'],
            'reference' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string'],
        ]);

        $invoiceJustPaid = false;
        if (!empty($data['invoice_id'])) {
            $invoice = Invoice::query()->where('project_id', $project->id)->findOrFail($data['invoice_id']);
            $invoiceWasPaidBefore = (string) $invoice->status === 'paid';
            $invoice->paid_amount = (float) $invoice->paid_amount + (float) $data['amount'];
            $invoice->status = $invoice->paid_amount >= (float) $invoice->amount ? 'paid' : 'partially_paid';
            $invoice->save();
            $invoiceJustPaid = !$invoiceWasPaidBefore && (string) $invoice->status === 'paid';
        }

        $payment = $project->payments()->create($data);

        $project->paid_total = (float) $project->payments()->sum('amount');
        $project->save();

        $message = 'Payment logged and project balance updated.';

        if (isset($invoice)) {
            $this->sendPaymentReceivedEmail($project->load('client'), $invoice, $payment);
            $this->sendAdminOrderAlertEmail($project->load('client'), $invoice, $payment);
            if ($invoiceJustPaid) {
                $this->sendReviewRequestEmail($project->load('client'), $invoice, $payment);
            }
            $message = 'Payment logged, project balance updated, and receipt emailed.';
        }

        return back()->with('success', $message);
    }

    public function editInvoiceStudio(Project $project, Invoice $invoice): View
    {
        $this->assertInvoiceBelongsToProject($project, $invoice);
        $project->loadMissing('client');

        $this->ensureInvoicePublicToken($invoice);

        $payload = $this->normalizeInvoicePayload($project, $invoice, (array) ($invoice->invoice_payload ?? []));
        $publicUrl = route('invoice.public.show', ['token' => $invoice->public_token]);

        return view('admin.projects.invoice-studio', compact('project', 'invoice', 'payload', 'publicUrl'));
    }

    public function saveInvoiceStudio(Request $request, Project $project, Invoice $invoice): RedirectResponse
    {
        $this->assertInvoiceBelongsToProject($project, $invoice);
        $project->loadMissing('client');

        $data = $request->validate([
            'show_pay_button' => ['nullable', 'boolean'],
            'headline' => ['nullable', 'string', 'max:190'],
            'intro' => ['nullable', 'string', 'max:700'],
            'client_name' => ['nullable', 'string', 'max:180'],
            'client_company' => ['nullable', 'string', 'max:180'],
            'client_email' => ['nullable', 'email', 'max:180'],
            'client_phone' => ['nullable', 'string', 'max:70'],
            'project_summary' => ['nullable', 'string', 'max:2200'],
            'scope_points' => ['nullable', 'string', 'max:3500'],
            'terms' => ['nullable', 'string', 'max:2400'],
            'extra_notes' => ['nullable', 'string', 'max:2400'],
            'payment_label' => ['nullable', 'string', 'max:90'],
        ]);

        $payload = [
            'source' => trim((string) data_get($invoice->invoice_payload, 'source', 'admin_project')),
            'headline' => trim((string) ($data['headline'] ?? '')),
            'intro' => trim((string) ($data['intro'] ?? '')),
            'client_name' => trim((string) ($data['client_name'] ?? '')),
            'client_company' => trim((string) ($data['client_company'] ?? '')),
            'client_email' => trim((string) ($data['client_email'] ?? '')),
            'client_phone' => trim((string) ($data['client_phone'] ?? '')),
            'project_summary' => trim((string) ($data['project_summary'] ?? '')),
            'scope_points' => collect(preg_split('/\r\n|\r|\n/', (string) ($data['scope_points'] ?? '')))
                ->map(static fn ($line) => trim((string) $line))
                ->filter()
                ->values()
                ->all(),
            'terms' => trim((string) ($data['terms'] ?? '')),
            'extra_notes' => trim((string) ($data['extra_notes'] ?? '')),
            'payment_label' => trim((string) ($data['payment_label'] ?? '')),
        ];

        $this->ensureInvoicePublicToken($invoice);
        $invoice->invoice_payload = $payload;
        $invoice->show_pay_button = $request->boolean('show_pay_button', true);
        $invoice->save();

        return back()->with('success', 'Invoice studio content saved.');
    }

    public function sendInvoiceLink(Request $request, Project $project, Invoice $invoice): RedirectResponse
    {
        $this->assertInvoiceBelongsToProject($project, $invoice);
        $project->loadMissing('client');

        $data = $request->validate([
            'email' => ['required', 'email', 'max:180'],
            'subject' => ['nullable', 'string', 'max:170'],
            'link_mode' => ['nullable', 'in:invoice,payment,pdf,portal'],
        ]);

        $this->ensureInvoicePublicToken($invoice);

        $email = trim((string) $data['email']);
        $subject = trim((string) ($data['subject'] ?? ''));
        $linkMode = (string) ($data['link_mode'] ?? 'invoice');

        if ($subject === '') {
            $subject = $this->defaultInvoiceSubjectForMode($linkMode, $project, $invoice);
        }

        try {
            $this->sendInvoiceLinkEmail($project, $invoice, $email, $subject, $linkMode);
        } catch (\Throwable $e) {
            return back()->withErrors([
                'invoice_send' => 'Invoice link email could not be sent right now. Please check mail settings and try again.',
            ]);
        }

        return back()->with('success', 'Client link sent successfully.');
    }

    public function updateInvoiceStatus(Request $request, Project $project, Invoice $invoice): RedirectResponse
    {
        $this->assertInvoiceBelongsToProject($project, $invoice);
        $project->loadMissing('client');

        $data = $request->validate([
            'status' => ['required', 'in:unpaid,partially_paid,paid,overdue,cancelled,failed,pending,received,late,successful'],
            'send_email' => ['nullable', 'boolean'],
            'status_note' => ['nullable', 'string', 'max:300'],
        ]);

        $oldStatus = (string) $invoice->status;
        $newStatusRaw = (string) $data['status'];
        $newStatus = match ($newStatusRaw) {
            'received', 'successful' => 'paid',
            'late' => 'overdue',
            default => $newStatusRaw,
        };

        if ($newStatus === 'paid' && (float) $invoice->paid_amount < (float) $invoice->amount) {
            $invoice->paid_amount = (float) $invoice->amount;
        }

        $invoice->status = $newStatus;
        $invoice->save();

        if ($request->boolean('send_email', true)) {
            $this->sendInvoiceStatusEmail($project, $invoice, $oldStatus, (string) ($data['status_note'] ?? ''));
        }

        return back()->with('success', 'Invoice status updated to ' . str_replace('_', ' ', ucfirst($newStatus)) . '.');
    }

    private function validatedProjectData(Request $request): array
    {
        return $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'title' => ['required', 'string', 'max:180'],
            'type' => ['nullable', 'string', 'max:80'],
            'status' => ['required', 'in:planning,in_progress,on_hold,delivered,closed'],
            'start_date' => ['nullable', 'date'],
            'delivery_date' => ['nullable', 'date'],
            'delivery_months' => ['nullable', 'integer', 'min:1', 'max:36'],
            'budget_total' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:10'],
            'description' => ['nullable', 'string'],
        ]);
    }

    private function calculateDeliveryDate(?string $startDate, int $deliveryMonths): ?string
    {
        if (empty($startDate)) {
            return null;
        }

        return Carbon::parse($startDate)->addMonths(max(1, $deliveryMonths))->toDateString();
    }

    private function resolveCurrencyByClientId(int $clientId): string
    {
        $country = strtoupper((string) optional(Client::find($clientId))->country);
        $map = [
            'UK' => 'GBP',
            'UNITED KINGDOM' => 'GBP',
            'CANADA' => 'CAD',
            'USA' => 'USD',
            'UNITED STATES' => 'USD',
            'GERMANY' => 'EUR',
            'INDIA' => 'INR',
            'PAKISTAN' => 'PKR',
        ];

        return $map[$country] ?? 'USD';
    }

    private function assertInvoiceBelongsToProject(Project $project, Invoice $invoice): void
    {
        abort_unless((int) $invoice->project_id === (int) $project->id, 404);
    }

    private function ensureInvoicePublicToken(Invoice $invoice): void
    {
        if (!empty($invoice->public_token)) {
            return;
        }

        $invoice->public_token = Str::random(56);
        $invoice->save();
    }

    private function normalizeInvoicePayload(Project $project, Invoice $invoice, array $payload): array
    {
        return [
            'source' => trim((string) ($payload['source'] ?? 'admin_project')),
            'headline' => trim((string) ($payload['headline'] ?? ('Invoice for ' . $project->title))),
            'intro' => trim((string) ($payload['intro'] ?? 'Please review this invoice and proceed with payment if approved.')),
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
            'terms' => trim((string) ($payload['terms'] ?? 'Payment confirms kickoff approval. Delivery timeline starts from confirmed payment date.')),
            'extra_notes' => trim((string) ($payload['extra_notes'] ?? ((string) $invoice->notes))),
            'payment_label' => trim((string) ($payload['payment_label'] ?? 'Pay Securely with Stripe')),
        ];
    }

    private function sendInvoiceLinkEmail(Project $project, Invoice $invoice, string $email, string $subject, string $linkMode = 'invoice'): void
    {
        $payload = $this->normalizeInvoicePayload($project, $invoice, (array) ($invoice->invoice_payload ?? []));
        $publicUrl = route('invoice.public.show', ['token' => $invoice->public_token]);
        $paymentUrl = route('invoice.public.pay-now', ['token' => $invoice->public_token]);
        $printUrl = $publicUrl . '?print=1';
        $portalUrl = route('client.portal', ['token' => $project->portal_token]);
        $balance = max(0, (float) $invoice->amount - (float) $invoice->paid_amount);

        $primaryUrl = $publicUrl;
        $primaryCta = 'Open Invoice & Pay';
        if ($linkMode === 'payment') {
            $primaryUrl = $paymentUrl;
            $primaryCta = 'Open Payment Page';
        } elseif ($linkMode === 'pdf') {
            $primaryUrl = $printUrl;
            $primaryCta = 'Open Invoice PDF';
        } elseif ($linkMode === 'portal') {
            $primaryUrl = $portalUrl;
            $primaryCta = 'Open Client Portal';
        }

        try {
            Mail::send('emails.client-invoice-link', [
                'project' => $project,
                'invoice' => $invoice,
                'invoicePayload' => $payload,
                'publicUrl' => $publicUrl,
                'paymentUrl' => $paymentUrl,
                'printUrl' => $printUrl,
                'portalUrl' => $portalUrl,
                'primaryUrl' => $primaryUrl,
                'primaryCta' => $primaryCta,
                'balance' => $balance,
            ], function ($message) use ($email, $subject, $project) {
                $message->to($email, $project->client?->name ?: 'Client')
                    ->subject($subject);
            });

            $invoice->sent_to_email = $email;
            $invoice->sent_at = now();
            $invoice->save();
        } catch (\Throwable $e) {
            Log::error('Failed to send invoice link email.', [
                'project_id' => $project->id,
                'invoice_id' => $invoice->id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    private function sendInvoiceStatusEmail(Project $project, Invoice $invoice, string $oldStatus, string $note = ''): void
    {
        $clientEmail = trim((string) ($project->client?->email ?? ''));
        if ($clientEmail === '') {
            return;
        }

        $status = (string) $invoice->status;
        $statusLabel = str_replace('_', ' ', ucfirst($status));

        $statusText = match ($status) {
            'paid' => 'Payment has been confirmed and this invoice is now marked as paid.',
            'partially_paid' => 'A partial payment is logged on this invoice. Remaining balance is still due.',
            'overdue' => 'This invoice is now overdue. Please clear the balance to keep delivery on schedule.',
            'failed' => 'A recent payment attempt failed. Please retry using the payment link below.',
            'cancelled' => 'This invoice has been cancelled from billing.',
            'pending' => 'This invoice is pending review/payment confirmation.',
            default => 'This invoice is currently unpaid.',
        };

        try {
            Mail::send('emails.client-invoice-status', [
                'project' => $project,
                'invoice' => $invoice,
                'oldStatus' => $oldStatus,
                'newStatus' => $status,
                'statusLabel' => $statusLabel,
                'statusText' => $statusText,
                'note' => trim($note),
                'invoiceUrl' => !empty($invoice->public_token)
                    ? route('invoice.public.show', ['token' => $invoice->public_token])
                    : null,
                'paymentUrl' => !empty($invoice->public_token)
                    ? route('invoice.public.pay-now', ['token' => $invoice->public_token])
                    : null,
                'portalUrl' => route('client.portal', ['token' => $project->portal_token]),
            ], function ($message) use ($clientEmail, $project, $invoice, $statusLabel) {
                $message->to($clientEmail, $project->client?->name ?: 'Client')
                    ->subject('Invoice Status Update (' . $statusLabel . ') - ' . $invoice->invoice_number);
            });
        } catch (\Throwable $e) {
            Log::error('Failed to send invoice status update email.', [
                'project_id' => $project->id,
                'invoice_id' => $invoice->id,
                'status' => $status,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function sendInvoiceCreatedEmail(Project $project, Invoice $invoice): void
    {
        $clientEmail = $project->client?->email;
        if (empty($clientEmail)) {
            return;
        }

        $payload = [
            'project' => $project,
            'invoice' => $invoice,
            'portalUrl' => route('client.portal', $project->portal_token),
            'invoiceUrl' => !empty($invoice->public_token)
                ? route('invoice.public.show', ['token' => $invoice->public_token])
                : null,
        ];

        try {
            Mail::send('emails.client-invoice-created', $payload, function ($message) use ($clientEmail, $project, $invoice) {
                $message->to($clientEmail, $project->client?->name ?: 'Client')
                    ->subject('New Invoice - '.$invoice->invoice_number);
            });
        } catch (\Throwable $e) {
            Log::error('Failed to send invoice email.', [
                'project_id' => $project->id,
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function defaultInvoiceSubjectForMode(string $linkMode, Project $project, Invoice $invoice): string
    {
        return match ($linkMode) {
            'payment' => 'Payment Link - ' . $invoice->invoice_number,
            'pdf' => 'Invoice PDF - ' . $invoice->invoice_number,
            'portal' => 'Client Portal Access - ' . $project->title,
            default => 'Invoice Link - ' . $invoice->invoice_number,
        };
    }

    private function sendPaymentReceivedEmail(Project $project, Invoice $invoice, Payment $payment): void
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
            Mail::send('emails.client-payment-received', $payload, function ($message) use ($clientEmail, $project, $invoice) {
                $message->to($clientEmail, $project->client?->name ?: 'Client')
                    ->subject('Payment Received - '.$invoice->invoice_number);
            });
        } catch (\Throwable $e) {
            Log::error('Failed to send payment email.', [
                'project_id' => $project->id,
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    private function generateClientInvoiceNumber(Project $project): string
    {
        $year = now()->format('Y');
        $counter = max(1, (int) Invoice::query()->where('project_id', $project->id)->count() + 1);

        do {
            $candidate = 'CL-' . (int) $project->client_id . '-' . $year . '-' . str_pad((string) $counter, 4, '0', STR_PAD_LEFT);
            $exists = Invoice::query()->where('client_invoice_number', $candidate)->exists();
            $counter++;
        } while ($exists);

        return $candidate;
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
        } catch (\Throwable $e) {
            Log::error('Failed to send admin order alert email.', [
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
        } catch (\Throwable $e) {
            Log::error('Failed to send client review request email.', [
                'project_id' => $project->id,
                'invoice_id' => $invoice->id,
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
