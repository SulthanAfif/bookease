@extends('layouts.app')

@section('title', 'Riwayat Booking')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold">Riwayat Booking</h1>
        <a href="{{ route('booking.index') }}"
           class="text-sm bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
            + Booking Baru
        </a>
    </div>

    @if($bookings->isEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 py-16 text-center">
            <p class="text-slate-400 mb-4">Belum ada booking</p>
            <a href="{{ route('booking.index') }}" class="text-blue-600 hover:underline text-sm">
                Buat booking pertama →
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
                <a href="{{ route('booking.show', $booking) }}"
                   class="block bg-white rounded-2xl border border-slate-200 p-5 hover:border-blue-300 hover:shadow-md transition">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="font-semibold">
                                {{ \Carbon\Carbon::parse($booking->booking_date)->translatedFormat('l, d M Y') }}
                            </p>
                            <p class="text-sm text-slate-500">
                                {{ \Carbon\Carbon::parse($booking->start_time)->format('H:i') }}
                                –
                                {{ \Carbon\Carbon::parse($booking->end_time)->format('H:i') }}
                            </p>
                        </div>

                        @php
                            $statusClass = match($booking->status) {
                                'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                                'confirmed' => 'bg-blue-50 text-blue-700 border-blue-200',
                                'completed' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                'cancelled' => 'bg-red-50 text-red-700 border-red-200',
                                default => 'bg-slate-50 text-slate-600 border-slate-200',
                            };
                        @endphp

                        <span class="text-xs font-medium px-2.5 py-1 rounded-full border {{ $statusClass }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                    </div>

                    <div class="text-sm text-slate-600 space-y-1 mb-4">
                        <p>Staff: <span class="text-slate-800">{{ $booking->staff->name }}</span></p>
                        <p>Layanan: {{ $booking->services->pluck('name')->join(', ') }}</p>
                    </div>

                    <div class="flex justify-between items-center pt-3 border-t border-slate-100">
                        <span class="font-semibold text-blue-600">
                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                        </span>
                        <span class="text-xs text-slate-400">#{{ $booking->id }}</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
@endsection