<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactThankYouMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     */
   public function __construct(array $data)
{
    $data['user_message'] = $data['message'] ?? null;
    unset($data['message']); // reserved key hata do
    $this->data = $data;
}


    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Thank you for contacting us',
        );
    }

    /**
     * Get the message content definition.
     */
    // App\Mail\ContactThankYouMail.php

public function content(): \Illuminate\Mail\Mailables\Content
{
    return new \Illuminate\Mail\Mailables\Content(
        view: 'mails.contact-thank-you',
        with: [
            'first_name'   => $this->data['first_name'] ?? null,
            'last_name'    => $this->data['last_name'] ?? null,
            'email'        => $this->data['email'] ?? null,
            'phone'        => $this->data['phone'] ?? null,
            'subject'      => $this->data['subject'] ?? null,
            // ✅ yaha user_message hi read karo
            'user_message' => $this->data['user_message'] ?? null,
        ]
    );
}




    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}