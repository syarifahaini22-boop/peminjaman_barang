<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\LaporanController;

// Redirect root ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Auth::routes();

// Dashboard (hanya untuk authenticated users)
Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// Semua route harus dalam auth
Route::middleware(['auth'])->group(function () {
    // CRUD Mahasiswa
    Route::resource('mahasiswa', MahasiswaController::class);

    // Barang CRUD
    Route::resource('barang', BarangController::class);

    // TRASH
    Route::get('/barang/trash', [BarangController::class, 'trash'])->name('barang.trash');
    Route::post('/barang/{id}/restore', [BarangController::class, 'restore'])->name('barang.restore');
    Route::delete('/barang/{id}/force-delete', [BarangController::class, 'forceDelete'])->name('barang.force-delete');
    Route::post('/barang/restore-all', [BarangController::class, 'restoreAll'])->name('barang.restore-all');
    Route::post('/barang/empty-trash', [BarangController::class, 'emptyTrash'])->name('barang.empty-trash');

    // Peminjaman
    Route::resource('peminjaman', App\Http\Controllers\PeminjamanController::class);
    Route::post('/peminjaman/{id}/kembalikan', [App\Http\Controllers\PeminjamanController::class, 'kembalikan'])
        ->name('peminjaman.kembalikan');
    Route::get('/peminjaman/riwayat', [App\Http\Controllers\PeminjamanController::class, 'riwayat'])
        ->name('peminjaman.riwayat');

    // Laporan Controller - GUNAKAN NAMESPACE YANG BENAR
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/peminjaman', [LaporanController::class, 'peminjaman'])->name('peminjaman');
        Route::get('/pengembalian', [LaporanController::class, 'pengembalian'])->name('pengembalian');
        Route::get('/barang', [LaporanController::class, 'barang'])->name('barang');
        Route::get('/mahasiswa', [LaporanController::class, 'mahasiswa'])->name('mahasiswa');
        Route::get('/export/excel', [LaporanController::class, 'exportExcel'])->name('export.excel');
        Route::get('/export/pdf', [LaporanController::class, 'exportPdf'])->name('export.pdf');
        Route::get('/stats', [LaporanController::class, 'getStats'])->name('stats');
    });
});

// Jika tidak ditemukan
Route::fallback(function () {
    if (Auth::check()) return redirect()->route('dashboard');
    return redirect()->route('login');
});