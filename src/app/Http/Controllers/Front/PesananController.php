<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\DetailPesanan;
use App\Models\LayananLaundry;
use App\Models\Pelanggan;
use App\Models\PengaturanSistem;
use App\Models\Pesanan;
use App\Models\RiwayatStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller
{
    public function create()
    {
        $pengaturan = PengaturanSistem::query()
            ->where('status_sistem', 'aktif')
            ->first();

        $pelanggan = Pelanggan::query()
            ->where('user_id', auth()->id())
            ->first();

        $layananLaundries = LayananLaundry::query()
            ->with('kategoriLayanan')
            ->where('status', 'aktif')
            ->orderBy('nama_layanan')
            ->get();

        return view('front.pesanan.create', [
            'pengaturan' => $pengaturan,
            'pelanggan' => $pelanggan,
            'layananLaundries' => $layananLaundries,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nomor_whatsapp' => ['required', 'string', 'max:20'],
            'alamat' => ['nullable', 'string'],
            'layanan_laundry_id' => ['required', 'exists:layanan_laundries,id'],
            'berat' => ['nullable', 'numeric', 'min:0.1'],
            'jumlah_item' => ['nullable', 'integer', 'min:1'],
            'metode_penyerahan' => ['required', 'in:antar_sendiri,jemput'],
            'alamat_penjemputan' => ['nullable', 'string'],
            'catatan_pelanggan' => ['nullable', 'string'],
        ]);

        $layanan = LayananLaundry::query()
            ->where('status', 'aktif')
            ->findOrFail($request->layanan_laundry_id);

        if ($layanan->tipe_layanan === 'kiloan' && ! $request->berat) {
            return back()
                ->withErrors(['berat' => 'Berat cucian wajib diisi untuk layanan kiloan.'])
                ->withInput();
        }

        if ($layanan->tipe_layanan === 'satuan' && ! $request->jumlah_item) {
            return back()
                ->withErrors(['jumlah_item' => 'Jumlah item wajib diisi untuk layanan satuan.'])
                ->withInput();
        }

        if ($request->metode_penyerahan === 'jemput' && ! $request->alamat_penjemputan) {
            return back()
                ->withErrors(['alamat_penjemputan' => 'Alamat penjemputan wajib diisi jika memilih layanan jemput.'])
                ->withInput();
        }

        DB::transaction(function () use ($request, $layanan) {
            $pelanggan = Pelanggan::query()
                ->updateOrCreate(
                    ['user_id' => auth()->id()],
                    [
                        'nama_lengkap' => $request->nama_lengkap,
                        'email' => auth()->user()->email,
                        'nomor_whatsapp' => $request->nomor_whatsapp,
                        'alamat' => $request->alamat,
                        'status' => 'aktif',
                    ]
                );

            $jumlah = $layanan->tipe_layanan === 'kiloan'
                ? (float) $request->berat
                : (int) $request->jumlah_item;

            $subtotal = $jumlah * (float) $layanan->tarif;

            $tanggalMasuk = now();
            $estimasiSelesai = now()->addDays((int) $layanan->estimasi_hari);

            $pesanan = Pesanan::query()->create([
                'pelanggan_id' => $pelanggan->id,
                'nomor_pesanan' => $this->generateNomorPesanan(),
                'tanggal_masuk' => $tanggalMasuk,
                'estimasi_selesai' => $estimasiSelesai,
                'metode_penyerahan' => $request->metode_penyerahan,
                'alamat_penjemputan' => $request->metode_penyerahan === 'jemput'
                    ? $request->alamat_penjemputan
                    : null,
                'catatan_pelanggan' => $request->catatan_pelanggan,
                'status_pesanan' => 'menunggu_konfirmasi',
                'status_pembayaran' => 'belum_dibayar',
                'subtotal' => $subtotal,
                'diskon' => 0,
                'total_biaya' => $subtotal,
                'urutan_antrian' => $this->generateUrutanAntrian(),
            ]);

            DetailPesanan::query()->create([
                'pesanan_id' => $pesanan->id,
                'layanan_laundry_id' => $layanan->id,
                'nama_layanan' => $layanan->nama_layanan,
                'tipe_layanan' => $layanan->tipe_layanan,
                'berat' => $layanan->tipe_layanan === 'kiloan' ? $request->berat : null,
                'jumlah_item' => $layanan->tipe_layanan === 'satuan' ? $request->jumlah_item : null,
                'satuan_hitung' => $layanan->satuan_hitung,
                'harga_satuan' => $layanan->tarif,
                'subtotal' => $subtotal,
                'catatan' => $request->catatan_pelanggan,
            ]);

            RiwayatStatus::query()->create([
                'pesanan_id' => $pesanan->id,
                'user_id' => null,
                'status_sebelumnya' => null,
                'status_baru' => 'menunggu_konfirmasi',
                'tanggal_perubahan' => now(),
                'catatan' => 'Pesanan dibuat oleh pelanggan.',
            ]);

            session()->flash('success', 'Pesanan berhasil dibuat. Silakan pantau status pesanan secara berkala.');
            session()->put('nomor_pesanan_terakhir', $pesanan->nomor_pesanan);
        });

        return redirect()->route('front.pesanan.index');
    }

    private function generateNomorPesanan(): string
    {
        $tanggal = now()->format('Ymd');

        $jumlahHariIni = Pesanan::query()
            ->whereDate('created_at', now()->toDateString())
            ->count() + 1;

        return 'LDR-' . $tanggal . '-' . str_pad($jumlahHariIni, 4, '0', STR_PAD_LEFT);
    }

    private function generateUrutanAntrian(): int
    {
        return (int) Pesanan::query()->max('urutan_antrian') + 1;
    }
}