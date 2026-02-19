<?php

namespace App\Mail;

use App\Models\Lead;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class MeetingReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Lead $lead,
        public Carbon $meetingStart,
        public Carbon $meetingEnd,
        public string $kind = '24h',
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->kind === '2h'
                ? 'Reminder: Your ARSDeveloper meeting starts soon'
                : 'Reminder: Your ARSDeveloper meeting is tomorrow'
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.meeting-reminder',
            with: [
                'lead' => $this->lead,
                'meetingStart' => $this->meetingStart,
                'meetingEnd' => $this->meetingEnd,
                'kind' => $this->kind,
            ]
        );
    }
}

