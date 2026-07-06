<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pembayaran;
use App\Models\PengingatPengambilan;
use App\Models\Pesanan;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RingkasanOperasional extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalPesanan = Pesanan::count();

        $pesananAktif = Pesanan::whereNotIn('status_pesanan', [
            'selesai',
            'dibatalkan',
        ])->count();

        $cucianSiapDiambil = Pesanan::where('status_pesanan', 'siap_diambil')->count();

        $belumDibayar = Pesanan::where('status_pembayaran', 'belum_dibayar')->count();

        $reminderAktif = PengingatPengambilan::where('status_pengingat', 'aktif')->count();

        $totalPendapatan = Pembayaran::where('status_pembayaran', 'lunas')
            ->sum('total_tagihan');

        return [
            Stat::make('Total Pesanan', number_format($totalPesanan, 0, ',', '.'))
                ->description('Seluruh pesanan laundry')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('primary'),

            Stat::make('Pesanan Aktif', number_format($pesananAktif, 0, ',', '.'))
                ->description('Masih dalam proses operasional')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('warning'),

            Stat::make('Cucian Siap Diambil', number_format($cucianSiapDiambil, 0, ',', '.'))
                ->description('Menunggu pengambilan pelanggan')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Belum Dibayar', number_format($belumDibayar, 0, ',', '.'))
                ->description('Pesanan belum lunas')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),

            Stat::make('Reminder Pengambilan', number_format($reminderAktif, 0, ',', '.'))
                ->description('Cucian tertahan minimal 3 hari')
                ->descriptionIcon('heroicon-m-bell-alert')
                ->color('warning'),

            Stat::make('Total Pendapatan', 'Rp ' . number_format($totalPendapatan, 0, ',', '.'))
                ->description('Dari pembayaran lunas')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),
        ];
    }
}