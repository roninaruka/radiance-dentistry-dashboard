<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $messageContent)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Test Email from Radiance Dentistry Dashboard',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.test-email',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
