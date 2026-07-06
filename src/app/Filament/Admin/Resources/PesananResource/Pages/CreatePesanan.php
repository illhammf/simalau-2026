<?php

namespace App\Filament\Admin\Resources\PesananResource\Pages;

use App\Filament\Admin\Resources\PesananResource;
use App\Models\RiwayatStatus;
use Filament\Resources\Pages\CreateRecord;

class CreatePesanan extends CreateRecord
{
    protected static string $resource = PesananResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (empty($data['nomor_pesanan'])) {
            $data['nomor_pesanan'] = $this->generateNomorPesanan();
        }

        if (empty($data['tanggal_masuk'])) {
            $data['tanggal_masuk'] = now();
        }

        if (empty($data['urutan_antrian'])) {
            $data['urutan_antrian'] = $this->generateUrutanAntrian();
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        RiwayatStatus::query()->create([
            'pesanan_id' => $this->record->id,
            'user_id' => auth()->id(),
            'status_sebelumnya' => null,
            'status_baru' => $this->record->status_pesanan,
            'tanggal_perubahan' => now(),
            'catatan' => 'Pesanan dibuat melalui admin.',
        ]);
    }

    private function generateNomorPesanan(): string
    {
        $tanggal = now()->format('Ymd');

        $jumlahHariIni = $this->getModel()::query()
            ->whereDate('created_at', now()->toDateString())
            ->count() + 1;

        return 'LDR-' . $tanggal . '-' . str_pad($jumlahHariIni, 4, '0', STR_PAD_LEFT);
    }

    private function generateUrutanAntrian(): int
    {
        return (int) $this->getModel()::query()->max('urutan_antrian') + 1;
    }
}