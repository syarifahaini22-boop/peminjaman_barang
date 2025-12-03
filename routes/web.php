<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;


// Root -> login
Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

// Dashboard
Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Semua route butuh login
Route::middleware(['auth'])->group(function () {

    Route::resource('mahasiswa', MahasiswaController::class);
    // Pastikan sudah ada
    Route::resource('barang', BarangController::class);
    Route::resource('peminjaman', PeminjamanController::class);
    Route::resource('mahasiswa', MahasiswaController::class);

    Route::get('/laporan/edit/{type}/{id}', [LaporanController::class, 'editFromLaporan'])
    ->name('laporan.edit');

    Route::get('/user', [UserController::class, 'index']);


    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::get('/laporan/pengembalian', [LaporanController::class, 'pengembalian'])->name('laporan.pengembalian');

    // Barang
    Route::resource('barang', App\Http\Controllers\BarangController::class);

    // Peminjaman
    Route::resource('peminjaman', App\Http\Controllers\PeminjamanController::class);
    Route::post('/peminjaman/{id}/kembalikan', [App\Http\Controllers\PeminjamanController::class, 'kembalikan'])
        ->name('peminjaman.kembalikan');

    // Laporan — bagian ini kamu tanya tadi
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/peminjaman', [LaporanController::class, 'peminjaman'])->name('peminjaman');
        Route::get('/pengembalian', [LaporanController::class, 'pengembalian'])->name('pengembalian');
        Route::get('/barang', [LaporanController::class, 'barang'])->name('barang');
        Route::get('/mahasiswa', [LaporanController::class, 'mahasiswa'])->name('mahasiswa');
        Route::get('/export/excel', [LaporanController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [LaporanController::class, 'exportPdf'])->name('export.pdf');
    });
});

// Fallback
Route::fallback(function () {
    if (Auth::check()) return redirect()->route('dashboard');
    return redirect()->route('login');
});
