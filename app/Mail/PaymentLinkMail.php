<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PaymentLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public array $details;

    /**
     * Create a new message instance.
     *
     * @param array $details {
     *   student_name: string,
     *   course_name:  string,
     *   amount:       string  (formatted, e.g. "20,000"),
     *   payment_link: string  (Razorpay short URL),
     *   expires_at:   string  (human-readable, e.g. "09 Sep 2026, 11:40 AM"),
     * }
     */
    public function __construct(array $details)
    {
        $this->details = $details;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address('digital@velearn.in', 'Praveen Kumar G'),
            replyTo: [
                new \Illuminate\Mail\Mailables\Address('digital@velearn.in', 'Praveen Kumar G'),
            ],
            subject: 'Your Payment Link is Ready – VeLearn 🔗',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payment_link',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
