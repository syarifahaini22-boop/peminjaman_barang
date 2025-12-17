<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AdminController;

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

    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/manage', [AdminController::class, 'index'])->name('manage');
        Route::post('/store', [AdminController::class, 'store'])->name('store');
        Route::put('/{id}', [AdminController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/verify', [AdminController::class, 'verify'])->name('verify');
    });

    // ==== PERBAIKAN URUTAN ROUTE YANG LEBIH TEPAT ====

    // 1. Route PENCARIAN dengan constraints
    Route::get('/barang/search', [BarangController::class, 'searchByKode'])
        ->name('barang.searchByKode')
        ->where('search', '[a-zA-Z0-9-]+'); // Pastikan hanya string, bukan 'create'

    Route::get('/mahasiswa/search', [MahasiswaController::class, 'searchByNim'])
        ->name('mahasiswa.searchByNim')
        ->where('search', '[0-9]+');

    // 2. Barang CRUD - TAMBAHKAN ROUTE CREATE SECARA EXPLICIT DULU
    Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');
    Route::post('/barang/store', [BarangController::class, 'store'])->name('barang.store');

    // 3. Lalu resource route dengan EXCLUDE create dan store
    Route::resource('barang', BarangController::class)->except(['create', 'store']);

    // 4. Mahasiswa CRUD
    Route::resource('mahasiswa', MahasiswaController::class);

    // 5. TRASH routes
    Route::get('/barang/trash', [BarangController::class, 'trash'])->name('barang.trash');
    Route::post('/barang/{id}/restore', [BarangController::class, 'restore'])->name('barang.restore');
    Route::delete('/barang/{id}/force-delete', [BarangController::class, 'forceDelete'])->name('barang.force-delete');
    Route::delete('/barang/{id}/hard-delete', [BarangController::class, 'hardDelete'])->name('barang.hardDelete');
    Route::post('/barang/restore-all', [BarangController::class, 'restoreAll'])->name('barang.restore-all');
    Route::post('/barang/empty-trash', [BarangController::class, 'emptyTrash'])->name('barang.empty-trash');

    // Debug route
    Route::get('/check-db', [BarangController::class, 'checkDatabase']);

    // 6. Peminjaman - TAMBAHKAN ROUTE CREATE SECARA EXPLICIT JUGA
    Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
    Route::post('/peminjaman/store', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::resource('peminjaman', PeminjamanController::class)->except(['create', 'store']);

    Route::post('/peminjaman/{id}/kembalikan', [PeminjamanController::class, 'kembalikan'])
        ->name('peminjaman.kembalikan');
    Route::get('/peminjaman/riwayat', [PeminjamanController::class, 'riwayat'])
        ->name('peminjaman.riwayat');
    // Laporan
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
