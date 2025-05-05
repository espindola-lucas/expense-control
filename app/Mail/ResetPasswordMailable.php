<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordmailable extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $resetPasswordUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $resetPasswordUrl)
    {
        $this->user = $user;
        $this->resetPasswordUrl = $resetPasswordUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recuperación de contraseña',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password',
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

    public function build(){
        return $this->subject('Recupera tu contraseña')
                    ->view('emails.reset-password')
                    ->with([
                        'user' => $this->user,
                        'resetPasswordUrl' => $this->resetPasswordUrl
                    ]);
    }
}
