<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SessionReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $session;
    public $admin;

    /**
     * Create a new message instance.
     * 
     * @param $admin - The admin user who will be notified
     * @param $session - The session for which the reminder is being sent
     */
    public function __construct($admin, $session)
    {
        $this->session = $session;
        $this->admin = $admin;
    }

    /**
     * Get the message envelope.
     * 
     * @return Envelope - The email envelope containing the subject
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Session Reminder',
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
            view: 'emails.session_reminder',
            with: [
                'student' => $this->session,
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
