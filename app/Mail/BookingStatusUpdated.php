<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public string $oldStatus
    ) {
        $this->booking->load(['staff', 'services', 'user']);
    }

    public function envelope(): Envelope
    {
        $label = match ($this->booking->status) {
            'confirmed' => 'Dikonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => ucfirst($this->booking->status),
        };

        return new Envelope(
            subject: "Booking #{$this->booking->id} {$label} - BookEase",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.booking-status-updated',
        );
    }
}
