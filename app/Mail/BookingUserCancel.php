<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingUserCancel extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $resortOwner;

    public function __construct($booking, $resortOwner)
    {
        $this->booking = $booking;
        $this->resortOwner = $resortOwner;
    }

    public function build()
    {
        return $this->from($this->resortOwner) // Set the sender's email to the resort owner's email
        ->subject('Your Booking Has Been Cancelled')
        ->view('emails.booking_cancelled')
        ->with([
            'booking' => $this->booking,
        ]);
    }
}
