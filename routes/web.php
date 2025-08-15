<?php

use App\Http\Controllers\ProdukController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Halaman utama, kalau belum login diarahkan ke login
Route::get('/', function () {
    return redirect()->route('pos.index');
})->middleware('auth');

// Semua route ini harus login dulu
Route::middleware(['auth'])->group(function () {
    Route::resource('produk', ProdukController::class);
    Route::resource('pelanggan', PelangganController::class);

    Route::get('transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('transaksi/store', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('riwayat-transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');

    Route::get('/pos', [TransaksiController::class, 'pos'])->name('pos.index');
    Route::post('/pos', [TransaksiController::class, 'store'])->name('pos.store');
});

// Route bawaan auth (login, register, dll)
Auth::routes();

// Setelah login, arahkan ke POS
Route::get('/home', function () {
    return redirect()->route('pos.index');
})->name('home');
