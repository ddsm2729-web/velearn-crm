<?php

namespace App\Mail;

use App\Models\PlacementApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForwardProfileToHrMail extends Mailable
{
    use Queueable, SerializesModels;

    public PlacementApplication $application;
    public string $linkType;
    public string $subjectText;
    public string $remarks;

    /**
     * Create a new message instance.
     */
    public function __construct(PlacementApplication $application, string $linkType = 'combined', string $subjectText = '', string $remarks = '')
    {
        $this->application = $application;
        $this->linkType = $linkType;
        $this->subjectText = $subjectText ?: ('Candidate Profile for ' . ($application->opening->job_role ?? 'Position'));
        $this->remarks = $remarks;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('digital@velearn.in', 'VeLearn Placement Cell'),
            replyTo: [
                new Address('digital@velearn.in', 'VeLearn Placement Cell'),
            ],
            subject: $this->subjectText,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.forward_profile_hr',
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
