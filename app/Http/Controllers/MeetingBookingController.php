<?php

namespace App\Http\Controllers;

use App\Mail\ContactAdminMail;
use App\Mail\ContactUserAcknowledgementMail;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class MeetingBookingController extends Controller
{
    public function confirmation(string $token): View
    {
        $lead = $this->findMeetingLead($token);

        return view('pages.meeting-confirmation', [
            'lead' => $lead,
            'meetingDateText' => $this->meetingDateText($lead),
            'meetingTimeText' => $this->meetingTimeText($lead),
        ]);
    }

    public function manage(string $token): View
    {
        $lead = $this->findMeetingLead($token);

        return view('pages.meeting-manage', [
            'lead' => $lead,
            'meetingDateText' => $this->meetingDateText($lead),
            'meetingTimeText' => $this->meetingTimeText($lead),
            'meetingSlots' => $this->meetingSlots(),
        ]);
    }

    public function reschedule(Request $request, string $token): RedirectResponse
    {
        $lead = $this->findMeetingLead($token);
        $meetingSlots = $this->meetingSlots();

        $data = $request->validate([
            'meeting_date' => ['required', 'date'],
            'meeting_slot' => ['required', 'string', 'in:' . implode(',', $meetingSlots)],
        ]);

        $newDate = trim((string) $data['meeting_date']);
        $newSlot = trim((string) $data['meeting_slot']);

        if ($this->isSlotAlreadyBooked($newDate, $newSlot, $lead->id)) {
            return back()->withInput()->with('error', 'This date or slot is not available. Please choose another option.');
        }

        $previousDate = $lead->meeting_date?->format('Y-m-d');
        $previousSlot = (string) ($lead->meeting_slot ?: '');

        $lead->update([
            'meeting_previous_date' => $previousDate ?: $lead->meeting_previous_date,
            'meeting_previous_slot' => $previousSlot !== '' ? $previousSlot : $lead->meeting_previous_slot,
            'meeting_date' => $newDate,
            'meeting_slot' => $newSlot,
            'status' => 'meeting_rescheduled',
            'meeting_rescheduled_at' => now(),
            'meeting_cancelled_at' => null,
            'meeting_reminder_24h_sent_at' => null,
            'meeting_reminder_2h_sent_at' => null,
        ]);

        $payload = $this->meetingPayload($lead, 'rescheduled');

        try {
            Mail::to((string) config('contact.inbox_email', 'info@arsdeveloper.co.uk'))
                ->send((new ContactAdminMail($payload))->replyTo($lead->email, $lead->name ?: 'Client'));
            Mail::to($lead->email)->send(new ContactUserAcknowledgementMail($payload));
        } catch (\Throwable $e) {
            // Email failure should not block already-rescheduled request.
        }

        return redirect()
            ->route('meeting.manage', ['token' => $lead->meeting_token])
            ->with('success', 'Meeting rescheduled successfully.');
    }

    public function cancel(string $token): RedirectResponse
    {
        $lead = $this->findMeetingLead($token);

        if ($lead->status !== 'cancelled') {
            $lead->update([
                'status' => 'cancelled',
                'meeting_cancelled_at' => now(),
                'meeting_reminder_24h_sent_at' => null,
                'meeting_reminder_2h_sent_at' => null,
            ]);

            $payload = $this->meetingPayload($lead, 'cancelled');

            try {
                Mail::to((string) config('contact.inbox_email', 'info@arsdeveloper.co.uk'))
                    ->send((new ContactAdminMail($payload))->replyTo($lead->email, $lead->name ?: 'Client'));
                Mail::to($lead->email)->send(new ContactUserAcknowledgementMail($payload));
            } catch (\Throwable $e) {
                // Keep cancel action completed even if email delivery fails.
            }
        }

        return redirect()
            ->route('meeting.manage', ['token' => $lead->meeting_token])
            ->with('success', 'Your meeting has been cancelled. You can reschedule anytime from this page.');
    }

    private function findMeetingLead(string $token): Lead
    {
        return Lead::query()
            ->where('type', 'meeting')
            ->where('meeting_token', trim((string) $token))
            ->firstOrFail();
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

    private function isSlotAlreadyBooked(string $date, string $slot, ?int $exceptLeadId = null): bool
    {
        if ($date === '' || $slot === '') {
            return false;
        }

        $query = Lead::query()
            ->where('type', 'meeting')
            ->whereDate('meeting_date', $date)
            ->where('meeting_slot', $slot)
            ->whereNotIn('status', ['cancelled', 'meeting_completed', 'no_show', 'closed']);

        if ($exceptLeadId !== null) {
            $query->where('id', '!=', $exceptLeadId);
        }

        return $query->exists();
    }

    private function meetingPayload(Lead $lead, string $event): array
    {
        $timezone = (string) ($lead->meeting_timezone ?: config('contact.meeting_timezone', 'Europe/London'));
        $formattedDate = $lead->meeting_date?->format('Y-m-d') ?: '';
        $subjectByEvent = [
            'booked' => 'Meeting Booking Confirmed',
            'rescheduled' => 'Meeting Rescheduled',
            'cancelled' => 'Meeting Cancelled',
        ];

        $mailSubjectByEvent = [
            'booked' => 'Meeting booked successfully - ARSDeveloper',
            'rescheduled' => 'Your meeting has been rescheduled - ARSDeveloper',
            'cancelled' => 'Your meeting has been cancelled - ARSDeveloper',
        ];

        $messages = [
            'booked' => 'Your meeting slot is locked. Please keep this email for manage links.',
            'rescheduled' => 'Your meeting schedule has been updated as requested.',
            'cancelled' => 'Your meeting has been cancelled. You can rebook from the same manage link anytime.',
        ];

        $payload = [
            'form_type' => 'meeting',
            'meeting_event' => $event,
            'name' => (string) ($lead->name ?: 'Client'),
            'email' => (string) $lead->email,
            'phone' => (string) ($lead->phone ?: ''),
            'company' => (string) ($lead->company ?: ''),
            'subject' => $subjectByEvent[$event] ?? 'Meeting Update',
            'mail_subject' => $mailSubjectByEvent[$event] ?? 'Meeting update - ARSDeveloper',
            'message' => $messages[$event] ?? 'Meeting update.',
            'meeting_date' => $formattedDate,
            'meeting_slot' => (string) ($lead->meeting_slot ?: ''),
            'meeting_timezone' => $timezone,
            'project_type' => (string) ($lead->project_type ?: ''),
            'budget_range' => (string) ($lead->budget_range ?: ''),
            'meeting_reference' => 'MTG-' . str_pad((string) $lead->id, 6, '0', STR_PAD_LEFT),
            'meeting_confirmation_url' => route('meeting.confirmation', ['token' => $lead->meeting_token]),
            'meeting_manage_url' => route('meeting.manage', ['token' => $lead->meeting_token]),
            'meeting_cancel_url' => route('meeting.cancel', ['token' => $lead->meeting_token]),
            'submitted_at' => now()->toDateTimeString(),
            'submitted_from' => 'Meeting self-service',
            'ip' => '',
            'user_agent' => 'meeting-management-flow',
        ];

        if ($lead->meeting_previous_date && $lead->meeting_previous_slot) {
            $payload['meeting_previous_date'] = $lead->meeting_previous_date->format('Y-m-d');
            $payload['meeting_previous_slot'] = (string) $lead->meeting_previous_slot;
        }

        $range = $this->meetingRange($formattedDate, (string) ($lead->meeting_slot ?: ''), $timezone);
        if ($range) {
            [$startAt, $endAt] = $range;
            $payload['meeting_date_label'] = $startAt->format('l, d M Y');
            $payload['meeting_time_label'] = $startAt->format('h:i A') . ' - ' . $endAt->format('h:i A');
        }

        return $payload;
    }

    private function meetingDateText(Lead $lead): string
    {
        return $lead->meeting_date?->format('l, d M Y') ?: '-';
    }

    private function meetingTimeText(Lead $lead): string
    {
        $timezone = (string) ($lead->meeting_timezone ?: config('contact.meeting_timezone', 'Europe/London'));
        $range = $this->meetingRange($lead->meeting_date?->format('Y-m-d') ?: '', (string) ($lead->meeting_slot ?: ''), $timezone);
        if (!$range) {
            return ((string) ($lead->meeting_slot ?: '-')) . ' (' . $timezone . ')';
        }

        [$startAt, $endAt] = $range;

        return $startAt->format('h:i A') . ' - ' . $endAt->format('h:i A') . ' (' . $timezone . ')';
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
}
