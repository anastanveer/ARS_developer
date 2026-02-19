<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedContact;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $query = Lead::query()->latest();

        if ($request->filled('q')) {
            $term = trim((string) $request->input('q'));
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%")
                    ->orWhere('company', 'like', "%{$term}%")
                    ->orWhere('subject', 'like', "%{$term}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', (string) $request->input('type'));
        }

        if ($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }

        $leads = $query->paginate(20)->withQueryString();

        return view('admin.leads.index', compact('leads'));
    }

    public function show(Lead $lead): View
    {
        $emailLogs = $lead->emailLogs()->latest()->limit(20)->get();

        return view('admin.leads.show', compact('lead', 'emailLogs'));
    }

    public function updateStatus(Request $request, Lead $lead): RedirectResponse
    {
        $allowedStatuses = $lead->statusChoices();
        $data = $request->validate([
            'status' => ['required', Rule::in($allowedStatuses)],
        ]);

        if ($lead->type === 'meeting') {
            if ($data['status'] === 'meeting_confirmed') {
                $data['meeting_confirmed_at'] = now();
                $data['meeting_cancelled_at'] = null;
            } elseif ($data['status'] === 'cancelled') {
                $data['meeting_cancelled_at'] = now();
            } elseif (in_array($data['status'], ['meeting_completed', 'no_show'], true)) {
                $data['meeting_cancelled_at'] = null;
            }
        }

        $lead->update($data);

        return back()->with('success', 'Lead status updated.');
    }

    public function block(Request $request, Lead $lead): RedirectResponse
    {
        $data = $request->validate([
            'reason' => ['nullable', 'string', 'max:255'],
            'block_email' => ['nullable', 'boolean'],
            'block_ip' => ['nullable', 'boolean'],
        ]);

        $blockEmail = $request->boolean('block_email', true);
        $blockIp = $request->boolean('block_ip');

        if ($blockEmail && !empty($lead->email)) {
            BlockedContact::firstOrCreate(
                ['email' => $lead->email, 'ip' => null],
                ['reason' => $data['reason'] ?? 'Blocked from lead detail', 'is_active' => true]
            );
        }

        if ($blockIp && !empty($lead->ip)) {
            BlockedContact::firstOrCreate(
                ['email' => null, 'ip' => $lead->ip],
                ['reason' => $data['reason'] ?? 'Blocked from lead detail', 'is_active' => true]
            );
        }

        $lead->update([
            'is_blocked' => true,
            'blocked_reason' => $data['reason'] ?? 'Blocked from admin',
        ]);

        return back()->with('success', 'Lead contact blocked successfully.');
    }
}
