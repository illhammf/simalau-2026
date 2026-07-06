@extends('front.layouts.app', [
    'title' => 'Pesanan Saya - ' . ($pengaturan->nama_laundry ?? 'LaundryKita'),
    'pengaturan' => $pengaturan,
])

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">Pesanan Saya</p>
                <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-slate-900">
                    Riwayat pesanan laundry.
                </h1>
                <p class="mt-5 text-lg leading-8 text-slate-600">
                    Pantau status cucian, pembayaran, estimasi selesai, dan detail layanan laundry.
                </p>
            </div>

            <a href="{{ route('front.pesanan.create') }}" class="rounded-xl bg-blue-600 px-5 py-3 text-center text-sm font-semibold text-white hover:bg-blue-700">
                Buat Pesanan Baru
            </a>
        </div>
    </div>
</section>

<section class="border-t border-slate-200 bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 rounded-2xl border border-green-200 bg-green-50 p-5 text-sm font-semibold text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (! $pelanggan)
            <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center">
                <h2 class="text-xl font-bold text-slate-900">Belum ada data pelanggan</h2>
                <p class="mt-2 text-sm text-slate-500">
                    Buat pesanan pertama untuk melengkapi data pelanggan.
                </p>
                <a href="{{ route('front.pesanan.create') }}" class="mt-6 inline-flex rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                    Buat Pesanan
                </a>
            </div>
        @else
            <div class="space-y-5">
                @forelse ($pesanans as $pesanan)
                    <a href="{{ route('front.pesanan.show', $pesanan->nomor_pesanan) }}" class="block rounded-3xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-300 hover:shadow-md">
                        <div class="flex flex-col justify-between gap-5 md:flex-row md:items-start">
                            <div>
                                <div class="flex flex-wrap items-center gap-3">
                                    <h2 class="text-xl font-bold text-slate-900">
                                        {{ $pesanan->nomor_pesanan }}
                                    </h2>

                                    <span class="rounded-full px-3 py-1 text-xs font-semibold
                                        @class([
                                            'bg-slate-100 text-slate-700' => $pesanan->status_pesanan === 'menunggu_konfirmasi',
                                            'bg-yellow-100 text-yellow-700' => $pesanan->status_pesanan === 'menunggu_proses',
                                            'bg-blue-100 text-blue-700' => in_array($pesanan->status_pesanan, ['sedang_dicuci', 'sedang_dikeringkan', 'sedang_disetrika']),
                                            'bg-green-100 text-green-700' => in_array($pesanan->status_pesanan, ['siap_diambil', 'selesai']),
                                            'bg-red-100 text-red-700' => $pesanan->status_pesanan === 'dibatalkan',
                                        ])
                                    ">
                                        {{ $pesanan->nama_status_pesanan }}
                                    </span>

                                    <span class="rounded-full px-3 py-1 text-xs font-semibold
                                        @class([
                                            'bg-yellow-100 text-yellow-700' => $pesanan->status_pembayaran === 'belum_dibayar',
                                            'bg-green-100 text-green-700' => $pesanan->status_pembayaran === 'lunas',
                                        ])
                                    ">
                                        {{ $pesanan->nama_status_pembayaran }}
                                    </span>
                                </div>

                                <div class="mt-4 grid gap-3 text-sm text-slate-600 sm:grid-cols-2 lg:grid-cols-3">
                                    <p>
                                        <span class="font-semibold text-slate-900">Tanggal Masuk:</span>
                                        {{ $pesanan->tanggal_masuk?->format('d M Y, H:i') ?? '-' }}
                                    </p>

                                    <p>
                                        <span class="font-semibold text-slate-900">Estimasi:</span>
                                        {{ $pesanan->estimasi_selesai?->format('d M Y, H:i') ?? '-' }}
                                    </p>

                                    <p>
                                        <span class="font-semibold text-slate-900">Penyerahan:</span>
                                        {{ $pesanan->nama_metode_penyerahan }}
                                    </p>
                                </div>
                            </div>

                            <div class="md:text-right">
                                <p class="text-sm text-slate-500">Total Biaya</p>
                                <p class="mt-1 text-2xl font-bold text-slate-900">
                                    Rp {{ number_format($pesanan->total_biaya, 0, ',', '.') }}
                                </p>
                                <p class="mt-2 text-sm font-semibold text-blue-600">
                                    Lihat detail →
                                </p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="rounded-3xl border border-dashed border-slate-300 bg-white p-10 text-center">
                        <h2 class="text-xl font-bold text-slate-900">Belum ada pesanan</h2>
                        <p class="mt-2 text-sm text-slate-500">
                            Pesanan laundry yang kamu buat akan muncul di halaman ini.
                        </p>
                        <a href="{{ route('front.pesanan.create') }}" class="mt-6 inline-flex rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-700">
                            Buat Pesanan Pertama
                        </a>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $pesanans->links() }}
            </div>
        @endif
    </div>
</section>
@endsection