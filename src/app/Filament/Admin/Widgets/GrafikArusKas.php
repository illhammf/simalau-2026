<?php

namespace App\Filament\Admin\Widgets;

use App\Models\ArusKas;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class GrafikArusKas extends ChartWidget
{
    protected static ?string $heading = 'Grafik Arus Kas 7 Hari Terakhir';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $tanggalAwal = now()->subDays(6)->startOfDay();
        $tanggalAkhir = now()->endOfDay();

        $dataKas = ArusKas::query()
            ->selectRaw('tanggal, jenis, SUM(nominal) as total')
            ->whereBetween('tanggal', [
                $tanggalAwal->toDateString(),
                $tanggalAkhir->toDateString(),
            ])
            ->groupBy('tanggal', 'jenis')
            ->orderBy('tanggal')
            ->get()
            ->groupBy(fn ($item) => Carbon::parse($item->tanggal)->format('Y-m-d'));

        $labels = [];
        $kasMasuk = [];
        $kasKeluar = [];

        for ($tanggal = $tanggalAwal->copy(); $tanggal->lte($tanggalAkhir); $tanggal->addDay()) {
            $tanggalKey = $tanggal->format('Y-m-d');

            $labels[] = $tanggal->format('d M');

            $kasMasuk[] = (float) optional(
                $dataKas->get($tanggalKey)?->firstWhere('jenis', 'masuk')
            )->total ?? 0;

            $kasKeluar[] = (float) optional(
                $dataKas->get($tanggalKey)?->firstWhere('jenis', 'keluar')
            )->total ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Kas Masuk',
                    'data' => $kasMasuk,
                ],
                [
                    'label' => 'Kas Keluar',
                    'data' => $kasKeluar,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}