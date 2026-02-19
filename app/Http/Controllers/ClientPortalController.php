<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

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

        $payment = $project->payments()->create([
            'invoice_id' => $invoice->id,
            'amount' => $paidAmount,
            'payment_date' => now()->toDateString(),
            'method' => $data['method'] ?: 'Portal Payment',
            'reference' => $data['reference'] ?: null,
            'notes' => 'Paid by client via portal.',
        ]);

        $invoice->paid_amount = (float) $invoice->paid_amount + $paidAmount;
        $invoice->status = $invoice->paid_amount >= (float) $invoice->amount ? 'paid' : 'partially_paid';
        $invoice->save();

        $project->paid_total = (float) $project->payments()->sum('amount');
        $project->save();

        $this->sendPaymentEmail($project, $invoice, $payment);

        return back()->with('success', 'Payment recorded successfully. Receipt email sent.');
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

    private function sendPaymentEmail(Project $project, Invoice $invoice, $payment): void
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
        } catch (\Throwable $e) {
            Log::error('Failed to send payment receipt email from client portal.', [
                'project_id' => $project->id,
                'invoice_id' => $invoice->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
