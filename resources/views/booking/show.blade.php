@extends('layouts.app')

@section('title', 'Detail Booking #' . $booking->id)

@section('content')
    <div class="max-w-2xl mx-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <a href="{{ route('booking.history') }}" class="text-sm text-blue-600 hover:underline">← Kembali ke Riwayat</a>
                <h1 class="text-2xl font-bold mt-2">Detail Booking</h1>
            </div>
            <span class="text-sm text-gray-400">#{{ $booking->id }}</span>
        </div>

        {{-- Status Card --}}
        @php
            $statusColor = match($booking->status) {
                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                'confirmed' => 'bg-blue-100 text-blue-800 border-blue-200',
                'completed' => 'bg-green-100 text-green-800 border-green-200',
                'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                default => 'bg-gray-100 text-gray-800 border-gray-200',
            };
        @endphp

        <div class="bg-white rounded-2xl shadow-sm border p-6 mb-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Status</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $statusColor }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500 mb-1">Total</p>
                    <p class="text-xl font-bold text-blue-600">
                        Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-500">Tanggal</p>
                    <p class="font-medium">
                        {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d F Y') }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Waktu</p>
                    <p class="font-medium">
                        {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                        –
                        {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                    </p>
                </div>
                <div>
                    <p class="text-gray-500">Staff</p>
                    <p class="font-medium">{{ $booking->staff->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500">Dibuat</p>
                    <p class="font-medium">{{ $booking->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>

            @if($booking->notes)
                <div class="mt-5 pt-5 border-t">
                    <p class="text-gray-500 text-sm mb-1">Catatan</p>
                    <p class="text-sm">{{ $booking->notes }}</p>
                </div>
            @endif
        </div>

        {{-- Daftar Layanan --}}
        <div class="bg-white rounded-2xl shadow-sm border p-6 mb-6">
            <h2 class="font-semibold mb-4">Layanan yang Dipesan</h2>
            <div class="space-y-3">
                @foreach($booking->services as $service)
                    <div class="flex justify-between items-center py-2 border-b last:border-0">
                        <div>
                            <p class="font-medium">{{ $service->name }}</p>
                            <p class="text-sm text-gray-500">{{ $service->pivot->duration }} menit</p>
                        </div>
                        <p class="font-medium">
                            Rp {{ number_format($service->pivot->price, 0, ',', '.') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Tombol Aksi --}}
        @if($booking->status === 'pending')
            <form action="{{ route('booking.cancel', $booking) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                @csrf
                @method('PATCH')
                <button type="submit" class="w-full bg-red-50 text-red-600 border border-red-200 py-3 rounded-xl font-medium hover:bg-red-100 transition">
                    Batalkan Booking
                </button>
            </form>
        @endif
    </div>
@endsection