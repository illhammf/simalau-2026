@extends('front.layouts.app', [
    'title' => $pesanan->nomor_pesanan . ' - ' . ($pengaturan->nama_laundry ?? 'LaundryKita'),
    'pengaturan' => $pengaturan,
])

@section('content')
<section class="bg-white">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
        <a href="{{ route('front.pesanan.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">
            ← Kembali ke Pesanan Saya
        </a>

        <div class="mt-8 flex flex-col justify-between gap-5 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-600">Detail Pesanan</p>
                <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-slate-900">
                    {{ $pesanan->nomor_pesanan }}
                </h1>
                <p class="mt-4 text-lg text-slate-600">
                    Pesanan milik {{ $pesanan->pelanggan->nama_lengkap ?? '-' }}.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                <span class="rounded-full px-4 py-2 text-sm font-semibold
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

                <span class="rounded-full px-4 py-2 text-sm font-semibold
                    @class([
                        'bg-yellow-100 text-yellow-700' => $pesanan->status_pembayaran === 'belum_dibayar',
                        'bg-green-100 text-green-700' => $pesanan->status_pembayaran === 'lunas',
                    ])
                ">
                    {{ $pesanan->nama_status_pembayaran }}
                </span>
            </div>
        </div>
    </div>
</section>

<section class="border-t border-slate-200 bg-slate-50">
    <div class="mx-auto grid max-w-7xl gap-6 px-4 py-12 sm:px-6 lg:grid-cols-3 lg:px-8">
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900">Status Cucian</h2>

                <div class="mt-6 space-y-4">
                    @foreach ($pesanan->riwayatStatuses as $riwayat)
                        <div class="flex gap-4">
                            <div class="mt-1 h-3 w-3 rounded-full bg-blue-600"></div>
                            <div>
                                <p class="font-semibold text-slate-900">{{ $riwayat->nama_status_baru }}</p>
                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $riwayat->tanggal_perubahan?->format('d M Y, H:i') }}
                                </p>
                                @if ($riwayat->catatan)
                                    <p class="mt-2 text-sm text-slate-600">{{ $riwayat->catatan }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900">Detail Layanan</h2>

                <div class="mt-6 overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-slate-200 text-slate-500">
                            <tr>
                                <th class="py-3 pr-4">Layanan</th>
                                <th class="py-3 pr-4">Jumlah</th>
                                <th class="py-3 pr-4">Harga</th>
                                <th class="py-3 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($pesanan->detailPesanans as $detail)
                                <tr>
                                    <td class="py-4 pr-4 font-semibold text-slate-900">
                                        {{ $detail->nama_layanan }}
                                    </td>
                                    <td class="py-4 pr-4 text-slate-600">
                                        {{ $detail->jumlah_display }}
                                    </td>
                                    <td class="py-4 pr-4 text-slate-600">
                                        Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 text-right font-semibold text-slate-900">
                                        Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900">Ringkasan</h2>

                <div class="mt-6 space-y-4 text-sm">
                    <div class="flex justify-between gap-4">
                        <span class="text-slate-500">Tanggal Masuk</span>
                        <span class="font-semibold text-slate-900">{{ $pesanan->tanggal_masuk?->format('d M Y') ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-slate-500">Estimasi Selesai</span>
                        <span class="font-semibold text-slate-900">{{ $pesanan->estimasi_selesai?->format('d M Y') ?? '-' }}</span>
                    </div>

                    <div class="flex justify-between gap-4">
                        <span class="text-slate-500">Metode</span>
                        <span class="font-semibold text-slate-900">{{ $pesanan->nama_metode_penyerahan }}</span>
                    </div>

                    <div class="border-t border-slate-200 pt-4">
                        <div class="flex justify-between gap-4">
                            <span class="text-slate-500">Subtotal</span>
                            <span class="font-semibold text-slate-900">Rp {{ number_format($pesanan->subtotal, 0, ',', '.') }}</span>
                        </div>

                        <div class="mt-3 flex justify-between gap-4">
                            <span class="text-slate-500">Diskon</span>
                            <span class="font-semibold text-slate-900">Rp {{ number_format($pesanan->diskon, 0, ',', '.') }}</span>
                        </div>

                        <div class="mt-4 flex justify-between gap-4 text-lg">
                            <span class="font-bold text-slate-900">Total</span>
                            <span class="font-bold text-slate-900">Rp {{ number_format($pesanan->total_biaya, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-900">Pembayaran</h2>

                @if ($pesanan->pembayaran)
                    <div class="mt-6 space-y-4 text-sm">
                        <div class="flex justify-between gap-4">
                            <span class="text-slate-500">Nomor</span>
                            <span class="font-semibold text-slate-900">{{ $pesanan->pembayaran->nomor_pembayaran }}</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-slate-500">Metode</span>
                            <span class="font-semibold text-slate-900">{{ $pesanan->pembayaran->nama_metode_pembayaran }}</span>
                        </div>

                        <div class="flex justify-between gap-4">
                            <span class="text-slate-500">Status</span>
                            <span class="font-semibold text-slate-900">{{ $pesanan->pembayaran->nama_status_pembayaran }}</span>
                        </div>
                    </div>
                @else
                    <p class="mt-4 text-sm leading-6 text-slate-600">
                        Pembayaran belum dicatat oleh admin. Silakan lakukan pembayaran sesuai instruksi outlet.
                    </p>
                @endif
            </div>
        </aside>
    </div>
</section>
@endsection