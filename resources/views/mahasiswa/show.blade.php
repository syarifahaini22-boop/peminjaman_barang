@extends('layouts.app')

@section('title', 'Detail Mahasiswa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Profil Mahasiswa -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-circle"></i> Profil Mahasiswa
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        <div class="avatar-placeholder bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px; font-size: 2rem; color: white;">
                            {{ substr($mahasiswa->name, 0, 1) }}
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $mahasiswa->name }}</h4>
                    <p class="text-muted">{{ $mahasiswa->nim }}</p>
                    
                    <div class="text-start mt-4">
                        <p><i class="fas fa-envelope me-2"></i> {{ $mahasiswa->email }}</p>
                        <p><i class="fas fa-university me-2"></i> {{ $mahasiswa->fakultas }}</p>
                        <p><i class="fas fa-graduation-cap me-2"></i> {{ $mahasiswa->jurusan }}</p>
                        <p><i class="fas fa-phone me-2"></i> {{ $mahasiswa->no_hp ?? '-' }}</p>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('mahasiswa.edit', $mahasiswa->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Riwayat Peminjaman -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-history"></i> Riwayat Peminjaman
                    </h5>
                </div>
                <div class="card-body">
                    @if($riwayat->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Barang</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($riwayat as $item)
                                        <tr>
                                            <td><strong>{{ $item->kode_peminjaman }}</strong></td>
                                            <td>{{ $item->barang->nama }}</td>
                                            <td>{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                                            <td>{{ $item->tanggal_kembali->format('d/m/Y') }}</td>
                                            <td>
                                                @if($item->status == 'dipinjam')
                                                    <span class="badge bg-warning">Dipinjam</span>
                                                @elseif($item->status == 'dikembalikan')
                                                    <span class="badge bg-success">Dikembalikan</span>
                                                @else
                                                    <span class="badge bg-danger">Terlambat</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('peminjaman.show', $item->id) }}" 
                                                   class="btn btn-sm btn-info" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $riwayat->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Belum ada riwayat peminjaman</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Card -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Total Peminjaman</h6>
                            <h3 class="card-text">{{ $mahasiswa->peminjaman()->count() }}</h3>
                        </div>
                        <i class="fas fa-hand-holding fa-2x align-self-center"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Sedang Dipinjam</h6>
                            <h3 class="card-text">{{ $mahasiswa->peminjaman()->where('status', 'dipinjam')->count() }}</h3>
                        </div>
                        <i class="fas fa-clock fa-2x align-self-center"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Sudah Kembali</h6>
                            <h3 class="card-text">{{ $mahasiswa->peminjaman()->where('status', 'dikembalikan')->count() }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x align-self-center"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Terlambat</h6>
                            <h3 class="card-text">{{ $mahasiswa->peminjaman()->where('status', 'terlambat')->count() }}</h3>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x align-self-center"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Back Button -->
    <div class="mt-4">
        <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>
</div>

<style>
.avatar-placeholder {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: #0d6efd;
    color: white;
    font-weight: bold;
    border-radius: 50%;
}
</style>
@endsection