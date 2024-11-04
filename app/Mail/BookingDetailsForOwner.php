<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingDetailsForOwner extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $user;
    public $imagePath;

    /**
     * Create a new message instance.
     */
    public function __construct($booking, $user, $imagePath)
    {
        $this->booking = $booking;
        $this->user = $user;
        $this->imagePath = $imagePath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Booking for Your Resort',
        );
    }

    /**
     * Get the message content definition.
     */


    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
    public function build()
    {
        return $this->subject('New Booking for Your Resort')
                    ->view('emails.booking_details_owner');
    }
}
