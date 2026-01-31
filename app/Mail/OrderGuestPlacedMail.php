<?php

namespace App\Mail;

use App\Models\OrderGuest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderGuestPlacedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public OrderGuest $order;

    /**
     * Create a new message instance.
     */
    public function __construct(OrderGuest $order)
    {
        $this->order = $order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Order Has Been Placed Successfully',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
       
        return new Content(
            view: 'mails.order-guest-placed', // 👈 your email view
            with: [
                'order' => $this->order,
                'items' => $this->order->orderItems,
            ]
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
