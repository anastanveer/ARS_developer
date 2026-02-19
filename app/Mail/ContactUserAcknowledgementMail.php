<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactUserAcknowledgementMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $payload)
    {
    }

    public function envelope(): Envelope
    {
        $isMeeting = ($this->payload['form_type'] ?? '') === 'meeting';
        $subject = trim((string) ($this->payload['mail_subject'] ?? ''));

        return new Envelope(
            subject: $subject !== ''
                ? $subject
                : ($isMeeting
                ? 'Meeting booked successfully - ARSDeveloper'
                : 'We received your message - ARSDeveloper')
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-user-ack'
        );
    }
}
