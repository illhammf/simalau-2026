<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    protected $fillable = [
        'pesanan_id',
        'nomor_pembayaran',
        'metode_pembayaran',
        'total_tagihan',
        'nominal_dibayar',
        'kembalian',
        'status_pembayaran',
        'tanggal_pembayaran',
        'catatan',
    ];

    protected $casts = [
        'total_tagihan' => 'decimal:2',
        'nominal_dibayar' => 'decimal:2',
        'kembalian' => 'decimal:2',
        'tanggal_pembayaran' => 'datetime',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function getTotalTagihanRupiahAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->total_tagihan, 0, ',', '.');
    }

    public function getNominalDibayarRupiahAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->nominal_dibayar, 0, ',', '.');
    }

    public function getKembalianRupiahAttribute(): string
    {
        return 'Rp ' . number_format((float) $this->kembalian, 0, ',', '.');
    }

    public function getNamaMetodePembayaranAttribute(): string
    {
        return match ($this->metode_pembayaran) {
            'tunai' => 'Tunai',
            'transfer_bank' => 'Transfer Bank',
            'qris' => 'QRIS',
            default => '-',
        };
    }

    public function getNamaStatusPembayaranAttribute(): string
    {
        return match ($this->status_pembayaran) {
            'belum_dibayar' => 'Belum Dibayar',
            'lunas' => 'Lunas',
            default => '-',
        };
    }
}