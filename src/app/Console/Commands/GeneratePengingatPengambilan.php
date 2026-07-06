<?php

namespace App\Console\Commands;

use App\Models\PengingatPengambilan;
use App\Models\Pesanan;
use Illuminate\Console\Command;

class GeneratePengingatPengambilan extends Command
{
    protected $signature = 'laundry:generate-pengingat-pengambilan';

    protected $description = 'Generate pengingat pengambilan untuk cucian siap diambil yang tertahan minimal 3 hari.';

    public function handle(): int
    {
        $pesanans = Pesanan::query()
            ->with('pelanggan')
            ->where('status_pesanan', 'siap_diambil')
            ->whereNotNull('tanggal_siap_diambil')
            ->whereDate('tanggal_siap_diambil', '<=', now()->subDays(3)->toDateString())
            ->get();

        $jumlahDibuat = 0;

        foreach ($pesanans as $pesanan) {
            $pengingat = PengingatPengambilan::query()->updateOrCreate(
                [
                    'pesanan_id' => $pesanan->id,
                ],
                [
                    'pelanggan_id' => $pesanan->pelanggan_id,
                    'tanggal_siap_diambil' => $pesanan->tanggal_siap_diambil,
                    'tanggal_masuk_pengingat' => $pesanan->tanggal_siap_diambil->copy()->addDays(3),
                    'jumlah_hari_tertahan' => $pesanan->tanggal_siap_diambil->diffInDays(now()),
                    'status_pengingat' => 'aktif',
                    'catatan' => 'Pengingat dibuat otomatis oleh sistem.',
                ]
            );

            if ($pengingat->wasRecentlyCreated) {
                $jumlahDibuat++;
            }
        }

        $this->info("Pengingat pengambilan berhasil diproses. Data baru: {$jumlahDibuat}.");

        return self::SUCCESS;
    }
}