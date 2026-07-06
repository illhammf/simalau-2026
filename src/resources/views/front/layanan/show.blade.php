@extends('front.layouts.app', [
    'title' => $layanan->nama_layanan . ' - ' . ($pengaturan->nama_laundry ?? 'LaundryKita'),
    'pengaturan' => $pengaturan,
])

@section('content')
<section class="bg-white">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 lg:grid-cols-2 lg:px-8">
        <div>
            <a href="{{ route('front.layanan.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
                ← Kembali ke daftar layanan
            </a>

            <p class="mt-8 text-sm font-semibold uppercase tracking-wide text-blue-600">
                {{ $layanan->kategoriLayanan->nama_kategori ?? '-' }}
            </p>

            <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-slate-900">
                {{ $layanan->nama_layanan }}
            </h1>

            <p class="mt-6 text-lg leading-8 text-slate-600">
                {{ $layanan->deskripsi ?? 'Layanan laundry tersedia untuk pelanggan.' }}
            </p>

            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('front.pesanan.create') }}" class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                    Buat Pesanan
                </a>

                <a href="{{ route('front.layanan.index') }}" class="rounded-xl border border-slate-300 px-5 py-3 text-sm font-semibold text-slate-700 hover:border-blue-600 hover:text-blue-600">
                    Lihat Layanan Lain
                </a>
            </div>
        </div>

        <div class="rounded-[2rem] border border-slate-200 bg-slate-50 p-6">
            <div class="rounded-[1.5rem] bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900">Informasi Layanan</h2>

                <div class="mt-6 space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <span class="text-sm text-slate-500">Tipe Layanan</span>
                        <span class="font-semibold text-slate-900">{{ ucfirst($layanan->tipe_layanan) }}</span>
                    </div>

                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <span class="text-sm text-slate-500">Tarif</span>
                        <span class="font-semibold text-slate-900">
                            Rp {{ number_format($layanan->tarif, 0, ',', '.') }} / {{ $layanan->satuan_hitung }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                        <span class="text-sm text-slate-500">Estimasi</span>
                        <span class="font-semibold text-slate-900">{{ $layanan->estimasi_hari }} hari</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-500">Minimal Order</span>
                        <span class="font-semibold text-slate-900">
                            {{ $layanan->minimal_order ? $layanan->minimal_order . ' ' . $layanan->satuan_hitung : '-' }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl bg-blue-50 p-4 text-sm leading-6 text-blue-800">
                    Estimasi dapat berubah jika ada antrean tinggi atau hari libur operasional.
                </div>
            </div>
        </div>
    </div>
</section>

@if ($layananTerkait->count())
    <section class="border-t border-slate-200 bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">Layanan Terkait</p>
                <h2 class="mt-2 text-3xl font-bold text-slate-900">Layanan lain dalam kategori yang sama.</h2>
            </div>

            <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($layananTerkait as $item)
                    <a href="{{ route('front.layanan.show', $item->slug) }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-300 hover:shadow-md">
                        <p class="text-sm font-semibold text-blue-600">
                            {{ $item->kategoriLayanan->nama_kategori ?? '-' }}
                        </p>

                        <h3 class="mt-2 text-lg font-bold text-slate-900">{{ $item->nama_layanan }}</h3>

                        <p class="mt-4 text-lg font-bold text-slate-900">
                            Rp {{ number_format($item->tarif, 0, ',', '.') }}
                            <span class="text-sm font-medium text-slate-500">/ {{ $item->satuan_hitung }}</span>
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif
@endsection