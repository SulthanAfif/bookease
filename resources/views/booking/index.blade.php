@extends('layouts.app')

@section('title', 'Pilih Layanan')

@section('content')
    <div class="text-center mb-10">
        <h1 class="text-3xl font-bold tracking-tight mb-2">Pilih Layanan</h1>
        <p class="text-slate-500">Pilih satu atau lebih layanan yang ingin kamu booking</p>
    </div>

    <form action="{{ route('booking.create') }}" method="GET">
        <div class="grid sm:grid-cols-2 gap-4 mb-10">
            @forelse($services as $service)
                <label class="group relative bg-white rounded-2xl border border-slate-200 p-5 cursor-pointer
                              hover:border-blue-400 hover:shadow-md transition-all duration-200">
                    <div class="flex gap-4">
                        <input type="checkbox" name="services[]" value="{{ $service->id }}"
                               class="mt-1 w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg group-hover:text-blue-600 transition">
                                {{ $service->name }}
                            </h3>
                            <p class="text-sm text-slate-500 mt-1 line-clamp-2">
                                {{ $service->description }}
                            </p>
                            <div class="flex items-center justify-between mt-4">
                                <span class="font-semibold text-blue-600">
                                    Rp {{ number_format($service->price, 0, ',', '.') }}
                                </span>
                                <span class="text-xs text-slate-400 bg-slate-100 px-2 py-1 rounded-full">
                                    {{ $service->duration }} menit
                                </span>
                            </div>
                        </div>
                    </div>
                </label>
            @empty
                <div class="col-span-2 text-center py-16 text-slate-400">
                    <p class="text-lg">Belum ada layanan tersedia</p>
                </div>
            @endforelse
        </div>

        <div class="flex justify-center">
            <button type="submit"
                    class="bg-blue-600 text-white px-8 py-3 rounded-xl font-medium
                           hover:bg-blue-700 active:scale-95 transition shadow-sm shadow-blue-200">
                Lanjut Pilih Jadwal →
            </button>
        </div>
    </form>
@endsection