@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4">Dashboard Sistem Peminjaman Barang</h1>
    
    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h6 class="card-title">Total Barang</h6>
                    <h3 class="card-text">{{ App\Models\Barang::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h6 class="card-title">Total Mahasiswa</h6>
                    <h3 class="card-text">{{ App\Models\User::where('role', 'mahasiswa')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h6 class="card-title">Peminjaman Aktif</h6>
                    <h3 class="card-text">{{ App\Models\Peminjaman::where('status', 'dipinjam')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h6 class="card-title">Peminjaman Terlambat</h6>
                    <h3 class="card-text">{{ App\Models\Peminjaman::where('status', 'terlambat')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('peminjaman.create') }}" class="btn btn-primary btn-lg w-100 mb-2">
                                <i class="fas fa-hand-holding me-2"></i> Pinjam Barang
                            </a>
                            <p class="text-muted small">Pinjamkan barang ke mahasiswa</p>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('mahasiswa.create') }}" class="btn btn-success btn-lg w-100 mb-2">
                                <i class="fas fa-user-plus me-2"></i> Tambah Mahasiswa
                            </a>
                            <p class="text-muted small">Tambah data mahasiswa baru</p>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('barang.create') }}" class="btn btn-info btn-lg w-100 mb-2">
                                <i class="fas fa-box me-2"></i> Tambah Barang
                            </a>
                            <p class="text-muted small">Tambah data barang baru</p>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('peminjaman.riwayat') }}" class="btn btn-secondary btn-lg w-100 mb-2">
                                <i class="fas fa-history me-2"></i> Riwayat Peminjaman
                            </a>
                            <p class="text-muted small">Lihat semua riwayat peminjaman</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Peminjaman Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @forelse(App\Models\Peminjaman::with(['barang', 'mahasiswa'])->latest()->limit(5)->get() as $peminjaman)
                            <div class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">{{ $peminjaman->barang->nama }}</h6>
                                    <small>
                                        @if($peminjaman->status == 'dipinjam')
                                            <span class="badge bg-warning">Dipinjam</span>
                                        @elseif($peminjaman->status == 'dikembalikan')
                                            <span class="badge bg-success">Dikembalikan</span>
                                        @else
                                            <span class="badge bg-danger">Terlambat</span>
                                        @endif
                                    </small>
                                </div>
                                <p class="mb-1">
                                    <small>
                                        <i class="fas fa-user"></i> {{ $peminjaman->mahasiswa->name }} |
                                        <i class="fas fa-calendar"></i> {{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}
                                    </small>
                                </p>
                            </div>
                        @empty
                            <div class="text-center py-3">
                                <p class="text-muted">Belum ada data peminjaman</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection