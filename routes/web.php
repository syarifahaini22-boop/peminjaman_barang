<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\Auth\LoginController;

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

    // Route untuk laporan
    Route::get('/laporan', function () {
        return view('laporan.index');
    })->name('laporan.index');
    
    // Juga tambahkan route peminjaman.riwayat dari error sebelumnya
    Route::get('/peminjaman/riwayat', function () {
        return view('peminjaman.riwayat');
    })->name('peminjaman.riwayat');

    // CRUD Mahasiswa — cukup 1 kali saja!
    Route::resource('mahasiswa', MahasiswaController::class);

    // Barang CRUD
    Route::resource('barang', App\Http\Controllers\BarangController::class);

    // TRASH
    Route::get('/barang/trash', [App\Http\Controllers\BarangController::class, 'trash'])->name('barang.trash');
    Route::post('/barang/{id}/restore', [App\Http\Controllers\BarangController::class, 'restore'])->name('barang.restore');
    Route::delete('/barang/{id}/force-delete', [App\Http\Controllers\BarangController::class, 'forceDelete'])->name('barang.force-delete');
    Route::post('/barang/restore-all', [App\Http\Controllers\BarangController::class, 'restoreAll'])->name('barang.restore-all');
    Route::post('/barang/empty-trash', [App\Http\Controllers\BarangController::class, 'emptyTrash'])->name('barang.empty-trash');

    // Peminjaman
    Route::resource('peminjaman', App\Http\Controllers\PeminjamanController::class);
    Route::post('/peminjaman/{id}/kembalikan', [App\Http\Controllers\PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembalikan');

    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/peminjaman', 'LaporanController@peminjaman')->name('peminjaman');
        Route::get('/pengembalian', 'LaporanController@pengembalian')->name('pengembalian');
        Route::get('/barang', 'LaporanController@barang')->name('barang');
        Route::get('/mahasiswa', 'LaporanController@mahasiswa')->name('mahasiswa');
        Route::get('/export/excel', 'LaporanController@exportExcel')->name('export.excel');
        Route::get('/export/pdf', 'LaporanController@exportPdf')->name('export.pdf');
    });
});

// Jika tidak ditemukan
Route::fallback(function () {
    if (Auth::check()) return redirect()->route('dashboard');
    return redirect()->route('login');
});
