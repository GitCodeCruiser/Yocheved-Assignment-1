<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RateStudent extends Mailable
{
    use Queueable, SerializesModels;

    public $student;
    public $admin;

    /**
     * Create a new message instance.
     * 
     * @param $student - The student object to be rated
     * @param $admin - The admin user who will rate the student
     */
    public function __construct($student, $admin)
    {
        $this->admin = $admin;
        $this->student = $student;
    }

    /**
     * Get the message envelope.
     * 
     * @return Envelope - The email envelope containing the subject
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Rate Student',
        );
    }

    /**
     * Get the message content definition.
     * 
     * @return Content - The content of the email, including the view and data
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.rate_student',
            with: [
                'student' => $this->student,
                'admin' => $this->admin
            ]
        );
    }

    /**
     * Get the attachments for the message.
     * 
     * @return array<int, \Illuminate\Mail\Mailables\Attachment> - An array of attachments (if any)
     */
    public function attachments(): array
    {
        return [];
    }
}
