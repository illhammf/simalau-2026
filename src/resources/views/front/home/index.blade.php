@extends('front.layouts.app', [
    'title' => ($pengaturan->nama_laundry ?? 'LaundryKita') . ' - Laundry Cepat dan Rapi',
    'pengaturan' => $pengaturan,
])

@section('content')
<section class="bg-white">
    <div class="mx-auto grid max-w-7xl items-center gap-10 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-24">
        <div>
            <p class="mb-4 inline-flex rounded-full bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700">
                Laundry online mudah dipantau
            </p>

            <h1 class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl">
                Cuci pakaian lebih mudah, status cucian bisa dipantau.
            </h1>

            <p class="mt-6 text-lg leading-8 text-slate-600">
                Pilih layanan laundry, buat pesanan, lalu pantau proses cucian mulai dari menunggu proses sampai siap diambil.
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('front.pesanan.create') }}" class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                    Buat Pesanan
                </a>
                <a href="{{ route('front.layanan.index') }}" class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 hover:border-blue-600 hover:text-blue-600">
                    Lihat Layanan
                </a>
            </div>

            <div class="mt-10 grid grid-cols-3 gap-4">
                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-2xl font-bold text-slate-900">{{ $layananUnggulan->count() }}+</p>
                    <p class="mt-1 text-sm text-slate-500">Layanan</p>
                </div>

                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-2xl font-bold text-slate-900">{{ $kategoriLayanan->count() }}+</p>
                    <p class="mt-1 text-sm text-slate-500">Kategori</p>
                </div>

                <div class="rounded-2xl border border-slate-200 p-4">
                    <p class="text-2xl font-bold text-slate-900">{{ $totalPesananSelesai }}+</p>
                    <p class="mt-1 text-sm text-slate-500">Selesai</p>
                </div>
            </div>
        </div>

        <div class="rounded-[2rem] bg-gradient-to-br from-blue-600 to-cyan-500 p-8 text-white shadow-xl">
            <div class="rounded-[1.5rem] bg-white/15 p-6 backdrop-blur">
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-50">Status Cucian</p>

                <div class="mt-6 space-y-4">
                    <div class="flex items-center justify-between rounded-2xl bg-white p-4 text-slate-900">
                        <span class="font-semibold">Menunggu Proses</span>
                        <span class="text-sm text-slate-500">FIFO</span>
                    </div>

                    <div class="flex items-center justify-between rounded-2xl bg-white p-4 text-slate-900">
                        <span class="font-semibold">Sedang Dicuci</span>
                        <span class="text-sm text-blue-600">Aktif</span>
                    </div>

                    <div class="flex items-center justify-between rounded-2xl bg-white p-4 text-slate-900">
                        <span class="font-semibold">Siap Diambil</span>
                        <span class="text-sm text-green-600">Selesai</span>
                    </div>
                </div>

                <p class="mt-6 text-sm leading-6 text-blue-50">
                    Sistem antrean diproses berdasarkan urutan masuk pesanan agar proses operasional lebih tertib.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="border-y border-slate-200 bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">Kategori Layanan</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-900">Pilih layanan sesuai kebutuhan cucian.</h2>
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($kategoriLayanan as $kategori)
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-900">{{ $kategori->nama_kategori }}</h3>
                    <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600">
                        {{ $kategori->deskripsi ?? 'Kategori layanan laundry tersedia.' }}
                    </p>
                    <p class="mt-4 text-sm font-semibold text-blue-600">
                        {{ $kategori->layanan_laundries_count }} layanan tersedia
                    </p>
                </div>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-6 text-slate-500">
                    Belum ada kategori layanan aktif.
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">Layanan Unggulan</p>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Daftar layanan laundry.</h2>
            </div>

            <a href="{{ route('front.layanan.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                Lihat semua layanan
            </a>
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($layananUnggulan as $layanan)
                <a href="{{ route('front.layanan.show', $layanan->slug) }}" class="rounded-2xl border border-slate-200 p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-300 hover:shadow-md">
                    <p class="text-sm font-semibold text-blue-600">
                        {{ $layanan->kategoriLayanan->nama_kategori ?? '-' }}
                    </p>

                    <h3 class="mt-2 text-lg font-bold text-slate-900">{{ $layanan->nama_layanan }}</h3>

                    <p class="mt-3 line-clamp-2 text-sm leading-6 text-slate-600">
                        {{ $layanan->deskripsi ?? 'Layanan laundry tersedia untuk pelanggan.' }}
                    </p>

                    <div class="mt-5 flex items-center justify-between">
                        <span class="text-lg font-bold text-slate-900">
                            Rp {{ number_format($layanan->tarif, 0, ',', '.') }}
                        </span>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                            / {{ $layanan->satuan_hitung }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 p-6 text-slate-500">
                    Belum ada layanan aktif.
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection