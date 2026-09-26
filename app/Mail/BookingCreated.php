<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingCreated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Booking $booking)
    {
        $this->booking->load(['staff', 'services', 'user']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Konfirmasi Booking #' . $this->booking->id . ' - BookEase',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.booking-created',
        );
    }
}
