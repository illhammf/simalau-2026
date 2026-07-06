<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\KategoriLayanan;
use App\Models\LayananLaundry;
use App\Models\PengaturanSistem;
use App\Models\Pesanan;

class HomeController extends Controller
{
    public function index()
    {
        $pengaturan = PengaturanSistem::query()
            ->where('status_sistem', 'aktif')
            ->first();

        $kategoriLayanan = KategoriLayanan::query()
            ->where('status', 'aktif')
            ->withCount([
                'layananLaundries' => fn ($query) => $query->where('status', 'aktif'),
            ])
            ->orderBy('urutan')
            ->limit(6)
            ->get();

        $layananUnggulan = LayananLaundry::query()
            ->with('kategoriLayanan')
            ->where('status', 'aktif')
            ->orderBy('tarif')
            ->limit(6)
            ->get();

        $totalPesananSelesai = Pesanan::query()
            ->where('status_pesanan', 'selesai')
            ->count();

        return view('front.home.index', [
            'pengaturan' => $pengaturan,
            'kategoriLayanan' => $kategoriLayanan,
            'layananUnggulan' => $layananUnggulan,
            'totalPesananSelesai' => $totalPesananSelesai,
        ]);
    }
}