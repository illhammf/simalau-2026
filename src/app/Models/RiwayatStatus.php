<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatStatus extends Model
{
    protected $fillable = [
        'pesanan_id',
        'user_id',
        'status_sebelumnya',
        'status_baru',
        'tanggal_perubahan',
        'catatan',
    ];

    protected $casts = [
        'tanggal_perubahan' => 'datetime',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getNamaStatusSebelumnyaAttribute(): string
    {
        return $this->formatStatus($this->status_sebelumnya);
    }

    public function getNamaStatusBaruAttribute(): string
    {
        return $this->formatStatus($this->status_baru);
    }

    private function formatStatus(?string $status): string
    {
        return match ($status) {
            'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
            'menunggu_proses' => 'Menunggu Proses',
            'sedang_dicuci' => 'Sedang Dicuci',
            'sedang_dikeringkan' => 'Sedang Dikeringkan',
            'sedang_disetrika' => 'Sedang Disetrika',
            'siap_diambil' => 'Siap Diambil',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            null => '-',
            default => '-',
        };
    }
}