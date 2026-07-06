<?php

namespace App\Filament\Admin\Resources\PembayaranResource\Pages;

use App\Filament\Admin\Resources\PembayaranResource;
use App\Models\ArusKas;
use App\Models\Pesanan;
use Filament\Resources\Pages\CreateRecord;

class CreatePembayaran extends CreateRecord
{
    protected static string $resource = PembayaranResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['nomor_pembayaran'])) {
            $data['nomor_pembayaran'] = $this->generateNomorPembayaran();
        }

        $totalTagihan = (float) ($data['total_tagihan'] ?? 0);
        $nominalDibayar = (float) ($data['nominal_dibayar'] ?? 0);

        $data['kembalian'] = max($nominalDibayar - $totalTagihan, 0);

        if (($data['status_pembayaran'] ?? null) === 'lunas' && empty($data['tanggal_pembayaran'])) {
            $data['tanggal_pembayaran'] = now();
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->syncPesananPembayaran();
        $this->syncArusKas();
    }

    private function syncPesananPembayaran(): void
    {
        Pesanan::query()
            ->where('id', $this->record->pesanan_id)
            ->update([
                'status_pembayaran' => $this->record->status_pembayaran,
            ]);
    }

    private function syncArusKas(): void
    {
        if ($this->record->status_pembayaran !== 'lunas') {
            return;
        }

        ArusKas::query()->updateOrCreate(
            [
                'pembayaran_id' => $this->record->id,
            ],
            [
                'pesanan_id' => $this->record->pesanan_id,
                'user_id' => auth()->id(),
                'jenis' => 'masuk',
                'kategori' => 'Pembayaran Laundry',
                'judul' => 'Pembayaran ' . $this->record->pesanan?->nomor_pesanan,
                'nominal' => $this->record->total_tagihan,
                'tanggal' => $this->record->tanggal_pembayaran?->toDateString() ?? now()->toDateString(),
                'keterangan' => 'Pembayaran laundry melalui ' . $this->record->nama_metode_pembayaran . '.',
            ]
        );
    }

    private function generateNomorPembayaran(): string
    {
        $tanggal = now()->format('Ymd');

        $jumlahHariIni = $this->getModel()::query()
            ->whereDate('created_at', now()->toDateString())
            ->count() + 1;

        return 'PAY-' . $tanggal . '-' . str_pad($jumlahHariIni, 4, '0', STR_PAD_LEFT);
    }
}