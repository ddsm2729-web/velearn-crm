<?php

namespace App\Mail;

use App\Models\ScheduleInterview;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InterviewScheduledMail extends Mailable
{
    use Queueable, SerializesModels;

    public ScheduleInterview $interview;

    /**
     * Create a new message instance.
     */
    public function __construct(ScheduleInterview $interview)
    {
        $this->interview = $interview;
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
            subject: 'Interview Call Letter: ' . ($this->interview->role ?: 'Interview') . ' at ' . $this->interview->company,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.interview_scheduled',
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
