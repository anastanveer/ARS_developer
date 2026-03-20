<?php

namespace App\Mail;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChatAdminNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public ChatConversation $conversation,
        public ChatMessage $message,
    ) {
    }

    public function envelope(): Envelope
    {
        $name = trim((string) ($this->conversation->name ?: 'Website Visitor'));

        return new Envelope(
            subject: 'New Website Chat: ' . $name
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.chat-admin',
            with: [
                'conversation' => $this->conversation,
                'chatMessage' => $this->message,
            ],
        );
    }
}
