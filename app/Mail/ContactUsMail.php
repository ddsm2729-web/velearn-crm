<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactUsMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $adminEmail = 'velearn@gmail.com';
        
        return new Envelope(
            from: new \Illuminate\Mail\Mailables\Address('digital@velearn.in', 'Praveen Kumar G'),
            to: [
                new \Illuminate\Mail\Mailables\Address($adminEmail, 'VeLearn Admin'),
            ],
            replyTo: [
                new \Illuminate\Mail\Mailables\Address('digital@velearn.in', 'Praveen Kumar G'),
            ],
            subject: 'New Contact Us Message - VeLearn',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact_us',
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
