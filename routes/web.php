<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
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

// Resource routes untuk aplikasi (hanya untuk authenticated users)
Route::middleware(['auth'])->group(function () {
    // Barang CRUD
    Route::resource('barang', App\Http\Controllers\BarangController::class);
    
    // ===== TRASH ROUTES =====
    Route::get('/barang/trash', [App\Http\Controllers\BarangController::class, 'trash'])->name('barang.trash');
    Route::post('/barang/{id}/restore', [App\Http\Controllers\BarangController::class, 'restore'])->name('barang.restore');
    Route::delete('/barang/{id}/force-delete', [App\Http\Controllers\BarangController::class, 'forceDelete'])->name('barang.force-delete');
    Route::post('/barang/restore-all', [App\Http\Controllers\BarangController::class, 'restoreAll'])->name('barang.restore-all');
    Route::post('/barang/empty-trash', [App\Http\Controllers\BarangController::class, 'emptyTrash'])->name('barang.empty-trash');
    
    // Peminjaman CRUD
    Route::resource('peminjaman', App\Http\Controllers\PeminjamanController::class);
    
    Route::post('/peminjaman/{id}/kembalikan', [App\Http\Controllers\PeminjamanController::class, 'kembalikan'])
        ->name('peminjaman.kembalikan');
    
    // Halaman lain
    Route::get('/mahasiswa', function () {
        return view('mahasiswa.index');
    })->name('mahasiswa.index');
    
    Route::get('/laporan', function () {
        return view('laporan.index');
    })->name('laporan.index');


    Route::get('/peminjaman/riwayat', [App\Http\Controllers\PeminjamanController::class, 'riwayat'])
        ->name('peminjaman.riwayat');
});

// Jika ada route yang tidak ditemukan, redirect ke dashboard
Route::fallback(function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});