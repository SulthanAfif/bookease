<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BookEase') - Booking Barbershop</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 min-h-screen text-slate-800 antialiased">

    {{-- Navbar --}}
    <nav class="bg-white/80 backdrop-blur border-b border-slate-200 sticky top-0 z-20">
        <div class="max-w-5xl mx-auto px-4 h-14 flex items-center justify-between">
            <a href="{{ route('booking.index') }}" class="flex items-center gap-2 font-bold text-lg text-blue-600">
                <span class="text-xl">✂</span>
                <span>BookEase</span>
            </a>

            <div class="flex items-center gap-3 text-sm">
                @auth
                    <a href="{{ route('booking.history') }}"
                       class="px-3 py-1.5 rounded-lg text-slate-600 hover:bg-slate-100 transition">
                        Riwayat
                    </a>
                    <div class="h-4 w-px bg-slate-200"></div>
                    <span class="text-slate-500">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-red-500 hover:text-red-600 transition">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-slate-600 hover:text-blue-600 transition">Login</a>
                    <a href="{{ route('register') }}"
                       class="bg-blue-600 text-white px-4 py-1.5 rounded-lg hover:bg-blue-700 transition">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <main class="max-w-5xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
                <span>✓</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                <span>!</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @yield('content')
    </main>

</body>
</html>