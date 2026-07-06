<?php

namespace App\Filament\Admin\Widgets;

use App\Models\PengingatPengambilan;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class PengingatPengambilanTerbaru extends BaseWidget
{
    protected static ?string $heading = 'Pengingat Pengambilan Terbaru';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                PengingatPengambilan::query()
                    ->with(['pesanan', 'pelanggan'])
                    ->whereIn('status_pengingat', [
                        'aktif',
                        'sudah_dihubungi',
                    ])
                    ->latest('tanggal_masuk_pengingat')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('pesanan.nomor_pesanan')
                    ->label('Nomor Pesanan')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Nomor pesanan berhasil disalin'),

                Tables\Columns\TextColumn::make('pelanggan.nama_lengkap')
                    ->label('Pelanggan')
                    ->description(fn (PengingatPengambilan $record): ?string => $record->pelanggan?->nomor_whatsapp)
                    ->searchable(),

                Tables\Columns\TextColumn::make('tanggal_siap_diambil')
                    ->label('Siap Diambil')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('tanggal_masuk_pengingat')
                    ->label('Masuk Pengingat')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('jumlah_hari_tertahan')
                    ->label('Tertahan')
                    ->formatStateUsing(fn ($state): string => $state . ' hari')
                    ->badge()
                    ->color('warning')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status_pengingat')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'aktif' => 'Aktif',
                        'sudah_dihubungi' => 'Sudah Dihubungi',
                        'selesai' => 'Selesai',
                        default => '-',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'warning',
                        'sudah_dihubungi' => 'info',
                        'selesai' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('tanggal_dihubungi')
                    ->label('Dihubungi')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('Belum dihubungi'),
            ])
            ->emptyStateHeading('Belum ada pengingat aktif')
            ->emptyStateDescription('Cucian yang siap diambil dan tertahan minimal 3 hari akan muncul di sini.')
            ->emptyStateIcon('heroicon-o-bell-alert');
    }
}