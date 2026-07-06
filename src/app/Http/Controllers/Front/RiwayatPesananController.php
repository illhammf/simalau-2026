<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\PengaturanSistem;
use App\Models\Pesanan;

class RiwayatPesananController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanSistem::query()
            ->where('status_sistem', 'aktif')
            ->first();

        $pelanggan = Pelanggan::query()
            ->where('user_id', auth()->id())
            ->first();

        $pesanans = Pesanan::query()
            ->with(['pelanggan', 'detailPesanans', 'pembayaran'])
            ->when($pelanggan, function ($query) use ($pelanggan) {
                $query->where('pelanggan_id', $pelanggan->id);
            })
            ->latest('tanggal_masuk')
            ->paginate(10);

        return view('front.pesanan.index', [
            'pengaturan' => $pengaturan,
            'pelanggan' => $pelanggan,
            'pesanans' => $pesanans,
        ]);
    }

    public function show(string $nomor_pesanan)
    {
        $pengaturan = PengaturanSistem::query()
            ->where('status_sistem', 'aktif')
            ->first();

        $pelanggan = Pelanggan::query()
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $pesanan = Pesanan::query()
            ->with([
                'pelanggan',
                'detailPesanans.layananLaundry',
                'pembayaran',
                'riwayatStatuses' => fn ($query) => $query->orderBy('tanggal_perubahan'),
            ])
            ->where('pelanggan_id', $pelanggan->id)
            ->where('nomor_pesanan', $nomor_pesanan)
            ->firstOrFail();

        return view('front.pesanan.show', [
            'pengaturan' => $pengaturan,
            'pelanggan' => $pelanggan,
            'pesanan' => $pesanan,
        ]);
    }
}