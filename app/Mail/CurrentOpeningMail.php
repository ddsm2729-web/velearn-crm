<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CurrentOpeningMail extends Mailable
{
    use Queueable, SerializesModels;

    public $opening;

    /**
     * Create a new message instance.
     */
    public function __construct($opening)
    {
        $this->opening = $opening;
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
            subject: 'Job Opening: ' . $this->opening->job_role,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.current_opening',
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
