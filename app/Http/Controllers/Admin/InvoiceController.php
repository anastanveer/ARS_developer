<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $query = Invoice::query()
            ->with(['project.client'])
            ->orderByDesc('invoice_date')
            ->orderByDesc('id');

        if ($request->filled('q')) {
            $term = trim((string) $request->input('q'));
            $query->where(function ($q) use ($term): void {
                $q->where('invoice_number', 'like', "%{$term}%")
                    ->orWhere('client_invoice_number', 'like', "%{$term}%")
                    ->orWhereHas('project', function ($projectQuery) use ($term): void {
                        $projectQuery->where('title', 'like', "%{$term}%")
                            ->orWhereHas('client', function ($clientQuery) use ($term): void {
                                $clientQuery->where('name', 'like', "%{$term}%")
                                    ->orWhere('company', 'like', "%{$term}%")
                                    ->orWhere('email', 'like', "%{$term}%");
                            });
                    });
            });
        }

        $statusFilter = trim((string) $request->input('status', ''));
        $status = match ($statusFilter) {
            'received', 'successful' => 'paid',
            'late' => 'overdue',
            default => $statusFilter,
        };
        if ($status !== '') {
            $query->where('status', $status);
        }

        $sourceFilterRaw = trim((string) $request->input('source', ''));
        $sourceFilter = match ($sourceFilterRaw) {
            'website' => 'website_direct_order',
            'manual' => 'admin_manual',
            'project' => 'admin_project',
            default => $sourceFilterRaw,
        };
        if ($sourceFilter !== '') {
            $query->where('invoice_payload->source', $sourceFilter);
        }

        $invoices = $query->paginate(20)->withQueryString();

        $projectsForInvoice = Project::query()
            ->with(['client:id,name,company,email'])
            ->orderByDesc('id')
            ->limit(250)
            ->get(['id', 'client_id', 'title', 'currency']);

        $stats = [
            'total' => (int) Invoice::query()->count(),
            'pending' => (int) Invoice::query()->where('status', 'pending')->count(),
            'overdue' => (int) Invoice::query()->where('status', 'overdue')->count(),
            'unpaid' => (int) Invoice::query()->where('status', 'unpaid')->count(),
            'paid' => (int) Invoice::query()->where('status', 'paid')->count(),
            'website_orders' => (int) Invoice::query()->where('invoice_payload->source', 'website_direct_order')->count(),
            'manual_admin' => (int) Invoice::query()->where('invoice_payload->source', 'admin_manual')->count(),
            'project_admin' => (int) Invoice::query()->where('invoice_payload->source', 'admin_project')->count(),
        ];

        return view('admin.invoices.index', compact('invoices', 'stats', 'status', 'statusFilter', 'sourceFilter', 'sourceFilterRaw', 'projectsForInvoice'));
    }

    public function storeDirectPaymentLink(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'client_name' => ['required', 'string', 'max:180'],
            'client_email' => ['required', 'email', 'max:180'],
            'client_company' => ['nullable', 'string', 'max:180'],
            'client_phone' => ['nullable', 'string', 'max:70'],
            'project_title' => ['required', 'string', 'max:180'],
            'project_type' => ['nullable', 'string', 'max:80'],
            'project_summary' => ['nullable', 'string', 'max:2200'],
            'requirements' => ['nullable', 'string', 'max:3500'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['nullable', 'string', 'max:10'],
            'invoice_date' => ['nullable', 'date'],
            'due_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1500'],
            'send_email' => ['nullable', 'boolean'],
            'link_mode' => ['nullable', 'in:invoice,payment,pdf,portal'],
            'email_subject' => ['nullable', 'string', 'max:170'],
        ]);

        $clientEmail = strtolower(trim((string) $data['client_email']));
        $currency = strtoupper(trim((string) ($data['currency'] ?? 'GBP')));
        if ($currency === '') {
            $currency = 'GBP';
        }

        $result = DB::transaction(function () use ($data, $clientEmail, $currency): array {
            $client = Client::query()->firstOrCreate(
                ['email' => $clientEmail],
                [
                    'name' => trim((string) $data['client_name']),
                    'company' => trim((string) ($data['client_company'] ?? '')),
                    'phone' => trim((string) ($data['client_phone'] ?? '')),
                ]
            );

            $client->fill([
                'name' => trim((string) $data['client_name']),
                'company' => trim((string) ($data['client_company'] ?? '')),
                'phone' => trim((string) ($data['client_phone'] ?? '')),
            ])->save();

            $project = Project::query()->create([
                'client_id' => (int) $client->id,
                'title' => trim((string) $data['project_title']),
                'type' => trim((string) ($data['project_type'] ?: Str::limit((string) $data['project_title'], 80, ''))),
                'status' => 'planning',
                'start_date' => now()->toDateString(),
                'delivery_date' => now()->addMonths(2)->toDateString(),
                'delivery_months' => 2,
                'budget_total' => (float) $data['amount'],
                'paid_total' => 0,
                'currency' => $currency,
                'portal_token' => Str::random(56),
                'description' => trim((string) ($data['project_summary'] ?: ($data['requirements'] ?? ''))),
            ]);

            if (trim((string) ($data['requirements'] ?? '')) !== '') {
                $project->requirements()->create([
                    'title' => 'Client Submitted Requirement',
                    'description' => trim((string) $data['requirements']),
                    'source' => 'client',
                    'status' => 'open',
                ]);
            }

            $invoiceDate = !empty($data['invoice_date']) ? (string) $data['invoice_date'] : now()->toDateString();
            $invoiceData = [
                'invoice_number' => $this->generateInvoiceNumber(),
                'invoice_date' => $invoiceDate,
                'due_date' => !empty($data['due_date']) ? (string) $data['due_date'] : now()->addDays(7)->toDateString(),
                'public_token' => Str::random(56),
                'amount' => (float) $data['amount'],
                'paid_amount' => 0,
                'status' => 'pending',
                'notes' => trim((string) ($data['notes'] ?? '')),
                'show_pay_button' => true,
                'invoice_payload' => [
                    'source' => 'admin_manual',
                    'headline' => 'Invoice for ' . trim((string) $data['project_title']),
                    'intro' => 'Please complete payment using the secure link below to start project delivery.',
                    'client_name' => trim((string) $data['client_name']),
                    'client_company' => trim((string) ($data['client_company'] ?? '')),
                    'client_email' => $clientEmail,
                    'client_phone' => trim((string) ($data['client_phone'] ?? '')),
                    'project_summary' => trim((string) ($data['project_summary'] ?? '')),
                    'scope_points' => collect(preg_split('/\r\n|\r|\n/', trim((string) ($data['requirements'] ?? ''))) ?: [])
                        ->map(static fn ($line) => trim((string) $line))
                        ->filter()
                        ->values()
                        ->all(),
                    'terms' => 'Payment confirms project kickoff. Milestone delivery starts from payment confirmation date.',
                    'extra_notes' => trim((string) ($data['notes'] ?? '')),
                    'payment_label' => 'Pay Now',
                ],
            ];

            if (Schema::hasColumn('invoices', 'client_invoice_number')) {
                $invoiceData['client_invoice_number'] = $this->generateClientInvoiceNumber($project);
            }

            $invoice = $project->invoices()->create($invoiceData);
            $project->loadMissing('client');

            return [$project, $invoice];
        });

        /** @var Project $project */
        $project = $result[0];
        /** @var Invoice $invoice */
        $invoice = $result[1];

        $linkMode = (string) ($data['link_mode'] ?? 'payment');
        $sendEmail = $request->boolean('send_email', true);
        $subject = trim((string) ($data['email_subject'] ?? ''));
        if ($subject === '') {
            $subject = $this->defaultInvoiceSubjectForMode($linkMode, $project, $invoice);
        }

        if ($sendEmail) {
            try {
                $this->sendInvoiceLinkEmail($project, $invoice, $clientEmail, $subject, $linkMode);
            } catch (\Throwable $e) {
                return redirect()->route('admin.invoices.index')->withErrors([
                    'direct_payment_link' => 'Payment link created but email could not be sent right now. You can resend from Invoice actions.',
                ]);
            }
        }

        return redirect()->route('admin.invoices.index')
            ->with('success', 'Direct payment link created successfully.')
            ->with('direct_invoice_url', route('invoice.public.show', ['token' => $invoice->public_token]))
            ->with('direct_payment_url', route('invoice.public.pay-now', ['token' => $invoice->public_token]))
            ->with('direct_pdf_url', route('invoice.public.show', ['token' => $invoice->public_token]) . '?print=1')
            ->with('direct_portal_url', route('client.portal', ['token' => $project->portal_token]));
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

    private function defaultInvoiceSubjectForMode(string $linkMode, Project $project, Invoice $invoice): string
    {
        return match ($linkMode) {
            'payment' => 'Payment Link - ' . $invoice->invoice_number,
            'pdf' => 'Invoice PDF - ' . $invoice->invoice_number,
            'portal' => 'Client Portal Access - ' . $project->title,
            default => 'Invoice Link - ' . $invoice->invoice_number,
        };
    }

    private function sendInvoiceLinkEmail(Project $project, Invoice $invoice, string $email, string $subject, string $linkMode = 'invoice'): void
    {
        $payload = [
            'headline' => trim((string) data_get($invoice->invoice_payload, 'headline', 'Invoice for ' . $project->title)),
            'project_summary' => trim((string) data_get($invoice->invoice_payload, 'project_summary', (string) $project->description)),
        ];
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
            Log::error('Failed to send direct payment link email.', [
                'project_id' => $project->id,
                'invoice_id' => $invoice->id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
