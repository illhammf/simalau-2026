<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Pesanan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PesananTerbaru extends BaseWidget
{
    protected static ?string $heading = 'Pesanan Terbaru';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Pesanan::query()
                    ->with(['pelanggan'])
                    ->latest('tanggal_masuk')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('nomor_pesanan')
                    ->label('Nomor Pesanan')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Nomor pesanan berhasil disalin'),

                Tables\Columns\TextColumn::make('pelanggan.nama_lengkap')
                    ->label('Pelanggan')
                    ->description(fn (Pesanan $record): ?string => $record->pelanggan?->nomor_whatsapp)
                    ->searchable(),

                Tables\Columns\TextColumn::make('status_pesanan')
                    ->label('Status Cucian')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'menunggu_konfirmasi' => 'Menunggu Konfirmasi',
                        'menunggu_proses' => 'Menunggu Proses',
                        'sedang_dicuci' => 'Sedang Dicuci',
                        'sedang_dikeringkan' => 'Sedang Dikeringkan',
                        'sedang_disetrika' => 'Sedang Disetrika',
                        'siap_diambil' => 'Siap Diambil',
                        'selesai' => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                        default => '-',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'menunggu_konfirmasi' => 'gray',
                        'menunggu_proses' => 'warning',
                        'sedang_dicuci' => 'info',
                        'sedang_dikeringkan' => 'info',
                        'sedang_disetrika' => 'info',
                        'siap_diambil' => 'success',
                        'selesai' => 'success',
                        'dibatalkan' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('status_pembayaran')
                    ->label('Pembayaran')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'belum_dibayar' => 'Belum Dibayar',
                        'lunas' => 'Lunas',
                        default => '-',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'belum_dibayar' => 'warning',
                        'lunas' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('total_biaya')
                    ->label('Total')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('tanggal_masuk')
                    ->label('Tanggal Masuk')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('-'),
            ])
            ->emptyStateHeading('Belum ada pesanan')
            ->emptyStateDescription('Pesanan terbaru akan muncul di tabel ini.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
    }
}