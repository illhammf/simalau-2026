<?php

use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\LayananController;
use App\Http\Controllers\Front\PesananController;
use App\Http\Controllers\Front\RiwayatPesananController;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

/* NOTE: Do Not Remove
/ Livewire asset handling if using sub folder in domain
*/

Livewire::setUpdateRoute(function ($handle) {
    return Route::post(config('app.asset_prefix') . '/livewire/update', $handle);
});

Livewire::setScriptRoute(function ($handle) {
    return Route::get(config('app.asset_prefix') . '/livewire/livewire.js', $handle);
});
/*
/ END
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('front.home');

Route::get('/layanan', [LayananController::class, 'index'])
    ->name('front.layanan.index');

Route::get('/layanan/{slug}', [LayananController::class, 'show'])
    ->name('front.layanan.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/pesanan', [RiwayatPesananController::class, 'index'])
        ->name('front.pesanan.index');

    Route::get('/pesanan/buat', [PesananController::class, 'create'])
        ->name('front.pesanan.create');

    Route::post('/pesanan', [PesananController::class, 'store'])
        ->name('front.pesanan.store');

    Route::get('/pesanan/{nomor_pesanan}', [RiwayatPesananController::class, 'show'])
        ->name('front.pesanan.show');
});