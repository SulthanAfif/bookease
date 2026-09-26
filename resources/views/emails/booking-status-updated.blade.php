<x-mail::message>
# Status Booking Diperbarui

Halo **{{ $booking->user->name }}**,

Status booking Anda telah diubah.

**Detail Booking:**

- **Nomor:** #{{ $booking->id }}
- **Status lama:** {{ ucfirst($oldStatus) }}
- **Status baru:** **{{ ucfirst($booking->status) }}**
- **Tanggal:** {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}
- **Waktu:** {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
- **Staff:** {{ $booking->staff->name }}
- **Total:** Rp {{ number_format($booking->total_price, 0, ',', '.') }}

@if($booking->status === 'confirmed')
Booking Anda sudah dikonfirmasi. Silakan datang sesuai jadwal.
@elseif($booking->status === 'completed')
Terima kasih telah menggunakan layanan BookEase. Sampai jumpa lagi!
@elseif($booking->status === 'cancelled')
Booking Anda telah dibatalkan. Jika ada pertanyaan, silakan hubungi kami.
@endif

Terima kasih,<br>
**BookEase**
</x-mail::message>
