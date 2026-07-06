<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'LaundryKita' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800">
    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/90 backdrop-blur">
        <nav class="container-app flex items-center justify-between py-4">
            <a href="{{ route('front.home') }}" class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-600 text-lg font-bold text-white shadow-sm">
                    L
                </div>
                <div>
                    <p class="text-lg font-bold leading-none text-slate-900">
                        {{ $pengaturan->nama_laundry ?? 'LaundryKita' }}
                    </p>
                    <p class="mt-1 text-xs text-slate-500">Laundry cepat dan rapi</p>
                </div>
            </a>

            <div class="hidden items-center gap-8 md:flex">
                <a href="{{ route('front.home') }}" class="nav-link">Beranda</a>
                <a href="{{ route('front.layanan.index') }}" class="nav-link">Layanan</a>

                @auth
                    <a href="{{ route('front.pesanan.index') }}" class="nav-link">Pesanan Saya</a>
                    <a href="{{ route('front.pesanan.create') }}" class="btn-primary py-2">
                        Buat Pesanan
                    </a>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary py-2">
                        Daftar
                    </a>
                @endauth
            </div>

            <button type="button" data-mobile-menu-button class="inline-flex rounded-xl border border-slate-200 p-2 text-slate-700 md:hidden">
                <span class="sr-only">Buka menu</span>
                ☰
            </button>
        </nav>

        <div data-mobile-menu class="mobile-menu">
            <div class="flex flex-col gap-3">
                <a href="{{ route('front.home') }}" class="nav-link">Beranda</a>
                <a href="{{ route('front.layanan.index') }}" class="nav-link">Layanan</a>

                @auth
                    <a href="{{ route('front.pesanan.index') }}" class="nav-link">Pesanan Saya</a>
                    <a href="{{ route('front.pesanan.create') }}" class="btn-primary">
                        Buat Pesanan
                    </a>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                    <a href="{{ route('register') }}" class="btn-primary">
                        Daftar
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-10 sm:px-6 md:grid-cols-3 lg:px-8">
            <div>
                <h2 class="text-lg font-bold text-slate-900">{{ $pengaturan->nama_laundry ?? 'LaundryKita' }}</h2>
                <p class="mt-3 text-sm leading-6 text-slate-600">
                    {{ $pengaturan->deskripsi ?? 'Layanan laundry kiloan dan satuan dengan proses rapi, cepat, dan mudah dipantau.' }}
                </p>
            </div>

            <div>
                <h3 class="font-semibold text-slate-900">Kontak</h3>
                <div class="mt-3 space-y-2 text-sm text-slate-600">
                    <p>WhatsApp: {{ $pengaturan->nomor_whatsapp ?? '-' }}</p>
                    <p>Email: {{ $pengaturan->email ?? '-' }}</p>
                    <p>Jam: {{ $pengaturan->jam_operasional ?? '-' }}</p>
                </div>
            </div>

            <div>
                <h3 class="font-semibold text-slate-900">Alamat</h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">
                    {{ $pengaturan->alamat ?? 'Alamat laundry belum diatur.' }}
                </p>
            </div>
        </div>

        <div class="border-t border-slate-200 py-4 text-center text-sm text-slate-500">
            © {{ date('Y') }} {{ $pengaturan->nama_laundry ?? 'LaundryKita' }}. All rights reserved.
        </div>
    </footer>
</body>
</html>