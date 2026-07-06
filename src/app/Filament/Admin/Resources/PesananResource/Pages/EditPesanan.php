<?php

namespace App\Filament\Admin\Resources\PesananResource\Pages;

use App\Filament\Admin\Resources\PesananResource;
use App\Models\PengingatPengambilan;
use App\Models\RiwayatStatus;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPesanan extends EditRecord
{
    protected static string $resource = PesananResource::class;

    protected ?string $statusSebelumnya = null;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->statusSebelumnya = $data['status_pesanan'] ?? null;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $statusBaru = $data['status_pesanan'] ?? null;

        if ($this->statusSebelumnya !== $statusBaru) {
            if ($statusBaru === 'siap_diambil' && empty($data['tanggal_siap_diambil'])) {
                $data['tanggal_siap_diambil'] = now();
            }

            if ($statusBaru === 'selesai' && empty($data['tanggal_selesai'])) {
                $data['tanggal_selesai'] = now();
            }
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $statusBaru = $this->record->status_pesanan;

        if ($this->statusSebelumnya === $statusBaru) {
            return;
        }

        RiwayatStatus::query()->create([
            'pesanan_id' => $this->record->id,
            'user_id' => auth()->id(),
            'status_sebelumnya' => $this->statusSebelumnya,
            'status_baru' => $statusBaru,
            'tanggal_perubahan' => now(),
            'catatan' => 'Status pesanan diperbarui melalui admin.',
        ]);

        if ($statusBaru === 'siap_diambil' && $this->record->tanggal_siap_diambil) {
            $tanggalMasukPengingat = $this->record->tanggal_siap_diambil->copy()->addDays(3);

            if (now()->greaterThanOrEqualTo($tanggalMasukPengingat)) {
                PengingatPengambilan::query()->updateOrCreate(
                    [
                        'pesanan_id' => $this->record->id,
                    ],
                    [
                        'pelanggan_id' => $this->record->pelanggan_id,
                        'tanggal_siap_diambil' => $this->record->tanggal_siap_diambil,
                        'tanggal_masuk_pengingat' => $tanggalMasukPengingat,
                        'jumlah_hari_tertahan' => $this->record->tanggal_siap_diambil->diffInDays(now()),
                        'status_pengingat' => 'aktif',
                    ]
                );
            }
        }

        if ($statusBaru === 'selesai') {
            PengingatPengambilan::query()
                ->where('pesanan_id', $this->record->id)
                ->update([
                    'status_pengingat' => 'selesai',
                ]);
        }

        $this->statusSebelumnya = $statusBaru;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}