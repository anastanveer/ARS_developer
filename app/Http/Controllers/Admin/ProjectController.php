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
            'invoice_number' => ['required', 'string', 'max:60', 'unique:invoices,invoice_number'],
            'invoice_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:unpaid,partially_paid,paid,overdue,cancelled'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['paid_amount'] = 0;
        $data['client_invoice_number'] = $this->generateClientInvoiceNumber($project);
        $invoice = $project->invoices()->create($data);
        $this->sendInvoiceCreatedEmail($project->load('client'), $invoice);

        return back()->with('success', 'Invoice added and emailed to client.');
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
