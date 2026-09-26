@extends('layouts.app')

@section('title', 'Pilih Jadwal')

@section('content')
    <div class="max-w-lg mx-auto">
        <div class="mb-8">
            <a href="{{ route('booking.index') }}" class="text-sm text-blue-600 hover:underline">← Kembali</a>
            <h1 class="text-2xl font-bold mt-2">Pilih Staff & Jadwal</h1>
        </div>

        {{-- Ringkasan Layanan --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-5 mb-6">
            <h2 class="text-sm font-medium text-slate-500 mb-3">Layanan dipilih</h2>
            <div class="space-y-2">
                @foreach($selectedServices as $service)
                    <div class="flex justify-between text-sm">
                        <span>{{ $service->name }}</span>
                        <span class="text-slate-600">Rp {{ number_format($service->price, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>
            <div class="border-t border-slate-100 mt-3 pt-3 flex justify-between font-semibold">
                <span>Total</span>
                <span class="text-blue-600">Rp {{ number_format($selectedServices->sum('price'), 0, ',', '.') }}</span>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('booking.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 p-6 space-y-5">
            @csrf

            @foreach($selectedServices as $service)
                <input type="hidden" name="services[]" value="{{ $service->id }}">
            @endforeach

            <div>
                <label class="block text-sm font-medium mb-1.5">Staff</label>
                <select name="staff_id" id="staff_id" required
                        class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Pilih staff</option>
                    @foreach($staffs as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Tanggal</label>
                <input type="date" name="booking_date" id="booking_date" required min="{{ date('Y-m-d') }}"
                       class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Jam Mulai</label>
                <select name="start_time" id="start_time" required
                        class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500">
                    <option value="">-- Pilih Staff & Tanggal dulu --</option>
                </select>
                <p class="text-xs text-slate-400 mt-1" id="time-info"></p>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1.5">Catatan <span class="text-slate-400">(opsional)</span></label>
                <textarea name="notes" rows="2"
                          class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500"
                          placeholder="Contoh: potongan pendek, dll"></textarea>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-xl font-medium
                           hover:bg-blue-700 active:scale-[0.98] transition shadow-sm shadow-blue-200">
                Konfirmasi Booking
            </button>
        </form>
    </div>

    <script>
        const staffSelect = document.getElementById('staff_id');
        const dateInput = document.getElementById('booking_date');
        const timeSelect = document.getElementById('start_time');
        const timeInfo = document.getElementById('time-info');
        const services = @json($selectedServices->pluck('id'));

        async function loadAvailableTimes() {
            const staffId = staffSelect.value;
            const date = dateInput.value;

            if (!staffId || !date) {
                timeSelect.innerHTML = '<option value="">-- Pilih Staff & Tanggal dulu --</option>';
                timeInfo.textContent = '';
                return;
            }

            timeSelect.innerHTML = '<option value="">Memuat jam tersedia...</option>';
            timeInfo.textContent = '';

            const params = new URLSearchParams({
                staff_id: staffId,
                booking_date: date,
            });
            services.forEach(id => params.append('services[]', id));

            try {
                const response = await fetch(`{{ route('booking.available-times') }}?${params.toString()}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });

                if (!response.ok) {
                    throw new Error('HTTP ' + response.status);
                }

                const data = await response.json();

                if (!data.times || data.times.length === 0) {
                    timeSelect.innerHTML = '<option value="">Tidak ada jam tersedia</option>';
                    timeInfo.textContent = 'Staff libur atau semua slot penuh.';
                } else {
                    timeSelect.innerHTML = '<option value="">-- Pilih Jam --</option>';
                    data.times.forEach(time => {
                        const option = document.createElement('option');
                        option.value = time;
                        option.textContent = time;
                        timeSelect.appendChild(option);
                    });
                    timeInfo.textContent = data.times.length + ' slot tersedia';
                }
            } catch (error) {
                console.error(error);
                timeSelect.innerHTML = '<option value="">Gagal memuat jam</option>';
                timeInfo.textContent = 'Coba refresh atau pilih tanggal lain.';
            }
        }

        staffSelect.addEventListener('change', loadAvailableTimes);
        dateInput.addEventListener('change', loadAvailableTimes);
    </script>
@endsection
