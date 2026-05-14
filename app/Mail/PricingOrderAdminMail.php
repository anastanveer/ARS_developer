<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PricingOrderAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $payload) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Package Order: ' . ($this->payload['plan'] ?? 'Unknown Plan')
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.pricing-order-admin');
    }
}
