<x-mail::message>
# Booking Berhasil Dibuat

Halo **{{ $booking->user->name }}**,

Terima kasih telah melakukan booking di **BookEase**.

**Detail Booking:**

- **Nomor:** #{{ $booking->id }}
- **Tanggal:** {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}
- **Waktu:** {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
- **Staff:** {{ $booking->staff->name }}
- **Status:** {{ ucfirst($booking->status) }}
- **Total:** Rp {{ number_format($booking->total_price, 0, ',', '.') }}

**Layanan:**
@foreach($booking->services as $service)
- {{ $service->name }} ({{ $service->pivot->duration }} menit) — Rp {{ number_format($service->pivot->price, 0, ',', '.') }}
@endforeach

@if($booking->notes)
**Catatan:** {{ $booking->notes }}
@endif

Kami akan menghubungi Anda jika ada perubahan status booking.

Terima kasih,<br>
**BookEase**
</x-mail::message>
