<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WebinarConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $webinar;
    public $registration;

    /**
     * Create a new message instance.
     */
    public function __construct($webinar, $registration)
    {
        $this->webinar = $webinar;
        $this->registration = $registration;
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
            subject: 'Webinar Registration Confirmed: ' . $this->webinar->title,
        );
    }


    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.webinar_confirmation',
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
