<?php

namespace App\Filament\Admin\Resources\PembayaranResource\Pages;

use App\Filament\Admin\Resources\PembayaranResource;
use App\Models\ArusKas;
use App\Models\Pesanan;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPembayaran extends EditRecord
{
    protected static string $resource = PembayaranResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $totalTagihan = (float) ($data['total_tagihan'] ?? 0);
        $nominalDibayar = (float) ($data['nominal_dibayar'] ?? 0);

        $data['kembalian'] = max($nominalDibayar - $totalTagihan, 0);

        if (($data['status_pembayaran'] ?? null) === 'lunas' && empty($data['tanggal_pembayaran'])) {
            $data['tanggal_pembayaran'] = now();
        }

        return $data;
    }

    protected function afterSave(): void
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
            ArusKas::query()
                ->where('pembayaran_id', $this->record->id)
                ->delete();

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

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}