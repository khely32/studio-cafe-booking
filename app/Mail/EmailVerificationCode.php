<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailVerificationCode extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $code)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Your 56'30 Studio Cafe verification code",
        );
    }

    public function content(): Content
    {
        return new Content(
            html: 'emails.email-verification-code',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}