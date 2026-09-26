<?php

namespace App\Http\Controllers;

use App\Mail\BookingCreated;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Service;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function index()
    {
        $services = Service::where('is_active', true)->get();

        return view('booking.index', compact('services'));
    }

    public function create(Request $request)
    {
        $selectedServices = Service::whereIn('id', $request->services ?? [])->get();

        if ($selectedServices->isEmpty()) {
            return redirect()->route('booking.index')->with('error', 'Pilih minimal 1 layanan');
        }

        $staffs = Staff::where('is_active', true)->get();

        return view('booking.create', compact('selectedServices', 'staffs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'services' => 'required|array|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $services = Service::whereIn('id', $request->services)->get();

        if ($services->isEmpty()) {
            return back()->with('error', 'Layanan tidak valid');
        }

        $totalPrice = $services->sum('price');
        $totalDuration = $services->sum('duration');

        $start = Carbon::parse($request->booking_date . ' ' . $request->start_time);
        $end = $start->copy()->addMinutes($totalDuration);

        $dayOfWeek = $start->dayOfWeek;
        $schedule = Schedule::where('staff_id', $request->staff_id)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (! $schedule || $schedule->is_day_off) {
            return back()->with('error', 'Staff libur di tanggal tersebut');
        }

        $workStart = Carbon::parse($request->booking_date . ' ' . $schedule->start_time);
        $workEnd = Carbon::parse($request->booking_date . ' ' . $schedule->end_time);

        if ($start->lt($workStart) || $end->gt($workEnd)) {
            return back()->with('error', 'Jam booking di luar jam kerja staff');
        }

        $conflict = Booking::where('staff_id', $request->staff_id)
            ->whereDate('booking_date', $request->booking_date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get()
            ->contains(function ($booking) use ($start, $end) {
                $bookingStart = Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->start_time);
                $bookingEnd = Carbon::parse($booking->booking_date->format('Y-m-d') . ' ' . $booking->end_time);

                return $start->lt($bookingEnd) && $end->gt($bookingStart);
            });

        if ($conflict) {
            return back()->with('error', 'Jam tersebut sudah dibooking. Silakan pilih jam lain.');
        }

        $booking = Booking::create([
            'user_id' => auth()->id(),
            'staff_id' => $request->staff_id,
            'booking_date' => $request->booking_date,
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'status' => 'pending',
            'notes' => $request->notes,
            'total_price' => $totalPrice,
        ]);

        foreach ($services as $service) {
            $booking->services()->attach($service->id, [
                'price' => $service->price,
                'duration' => $service->duration,
            ]);
        }

        // Kirim email konfirmasi (tidak menggagalkan booking jika email gagal)
        try {
            Mail::to($booking->user->email)->send(new BookingCreated($booking));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('booking.history')->with('success', 'Booking berhasil dibuat! Cek email untuk konfirmasi.');
    }

    public function history()
    {
        $bookings = Booking::with(['staff', 'services'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('booking.history', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['staff', 'services']);

        return view('booking.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'pending') {
            return back()->with('error', 'Hanya booking dengan status pending yang bisa dibatalkan.');
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    public function getAvailableTimes(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'booking_date' => 'required|date',
            'services' => 'required|array|min:1',
        ]);

        $staffId = $request->staff_id;
        $date = $request->booking_date;
        $services = Service::whereIn('id', $request->services)->get();
        $totalDuration = (int) $services->sum('duration');

        if ($totalDuration <= 0) {
            return response()->json(['times' => []]);
        }

        $dayOfWeek = Carbon::parse($date)->dayOfWeek;
        $schedule = Schedule::where('staff_id', $staffId)
            ->where('day_of_week', $dayOfWeek)
            ->first();

        if (! $schedule || $schedule->is_day_off) {
            return response()->json(['times' => []]);
        }

        $workStart = Carbon::parse($date . ' ' . $schedule->start_time);
        $workEnd = Carbon::parse($date . ' ' . $schedule->end_time);

        $existingBookings = Booking::where('staff_id', $staffId)
            ->whereDate('booking_date', $date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        $times = [];
        $current = $workStart->copy();

        while ($current->copy()->addMinutes($totalDuration)->lte($workEnd)) {
            $slotEnd = $current->copy()->addMinutes($totalDuration);

            $conflict = $existingBookings->contains(function ($booking) use ($current, $slotEnd, $date) {
                $bookingStart = Carbon::parse($date . ' ' . $booking->start_time);
                $bookingEnd = Carbon::parse($date . ' ' . $booking->end_time);

                return $current->lt($bookingEnd) && $slotEnd->gt($bookingStart);
            });

            if (! $conflict) {
                $times[] = $current->format('H:i');
            }

            $current->addMinutes(30);
        }

        return response()->json(['times' => $times]);
    }
}
