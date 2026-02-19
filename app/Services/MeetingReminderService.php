<?php

namespace App\Services;

use App\Mail\MeetingReminderMail;
use App\Models\Lead;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class MeetingReminderService
{
    public function sendDueReminders(): array
    {
        if (!Schema::hasTable('leads')
            || !Schema::hasColumn('leads', 'meeting_reminder_24h_sent_at')
            || !Schema::hasColumn('leads', 'meeting_reminder_2h_sent_at')) {
            return ['sent_24h' => 0, 'sent_2h' => 0];
        }

        $baseTimezone = (string) config('contact.meeting_timezone', 'Europe/London');
        $now = now($baseTimezone);
        $sent24 = 0;
        $sent2 = 0;

        Lead::query()
            ->where('type', 'meeting')
            ->whereNotNull('meeting_date')
            ->whereNotNull('meeting_slot')
            ->whereNotIn('status', ['cancelled', 'meeting_completed', 'no_show', 'closed'])
            ->orderBy('id')
            ->chunkById(100, function ($leads) use ($baseTimezone, $now, &$sent24, &$sent2) {
                foreach ($leads as $lead) {
                    $range = $this->meetingRange($lead->meeting_date?->format('Y-m-d') ?: '', (string) $lead->meeting_slot);
                    if (!$range) {
                        continue;
                    }

                    [$startAtBase, $endAtBase] = $range;
                    $minutesUntilStart = (int) floor(($startAtBase->timestamp - $now->timestamp) / 60);
                    $updated = [];
                    $leadTimezone = (string) ($lead->meeting_timezone ?: $baseTimezone);
                    $displayStart = $startAtBase->copy()->setTimezone($leadTimezone);
                    $displayEnd = $endAtBase->copy()->setTimezone($leadTimezone);

                    if ($lead->meeting_reminder_24h_sent_at === null && $minutesUntilStart >= 1380 && $minutesUntilStart <= 1500) {
                        Mail::to($lead->email)->send(new MeetingReminderMail($lead, $displayStart, $displayEnd, '24h'));
                        $updated['meeting_reminder_24h_sent_at'] = now();
                        $sent24++;
                    }

                    if ($lead->meeting_reminder_2h_sent_at === null && $minutesUntilStart >= 90 && $minutesUntilStart <= 150) {
                        Mail::to($lead->email)->send(new MeetingReminderMail($lead, $displayStart, $displayEnd, '2h'));
                        $updated['meeting_reminder_2h_sent_at'] = now();
                        $sent2++;
                    }

                    if ($updated !== []) {
                        $lead->forceFill($updated)->save();
                    }
                }
            });

        return ['sent_24h' => $sent24, 'sent_2h' => $sent2];
    }

    private function meetingRange(string $date, string $slot): ?array
    {
        $baseTimezone = (string) config('contact.meeting_timezone', 'Europe/London');
        $parts = preg_split('/\s*-\s*/', str_replace('–', '-', trim($slot))) ?: [];
        if (count($parts) !== 2) {
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

        return [$start->copy()->setTimezone($baseTimezone), $end->copy()->setTimezone($baseTimezone)];
    }
}
