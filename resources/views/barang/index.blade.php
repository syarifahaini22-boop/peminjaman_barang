@extends('layouts.app')

@section('title', 'Data Barang')
@section('content')
<div class="container-fluid">
    <!-- Page Header dengan Tombol Tambah -->
    <div class="navbar-custom">
        <div>
            <h2 class="h3 mb-1">Data Barang</h2>
            <p class="text-muted mb-0">Manajemen inventaris barang Lab RSI</p>
        </div>
        <a href="{{ route('barang.create') }}" class="btn btn-custom btn-custom-primary">
            <i class="bi bi-plus-circle me-2"></i> Tambah Barang
        </a>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-custom alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filter & Search Advanced -->
<div class="card card-custom mb-4">
    <div class="card-header card-header-custom">
        <h6 class="mb-0">
            <i class="bi bi-funnel me-2"></i> Filter & Pencarian
        </h6>
    </div>
    <div class="card-body">
        <form action="{{ route('barang.index') }}" method="GET" id="filterForm">
            <div class="row g-3">
                <!-- Search Input -->
                <div class="col-md-12 mb-3">
                    <label class="form-label form-label-custom">Pencarian</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control form-control-custom" 
                               placeholder="Cari berdasarkan kode, nama, merek, atau lokasi..." 
                               value="{{ $filters['search'] ?? '' }}">
                    </div>
                </div>
                
                <!-- Filter Row 1 -->
                <div class="col-md-3">
                    <label class="form-label form-label-custom">Kategori</label>
                    <select name="kategori" class="form-select form-control-custom">
                        <option value="">Semua Kategori</option>
                        <option value="elektronik" {{ ($filters['kategori'] ?? '') == 'elektronik' ? 'selected' : '' }}>Elektronik</option>
                        <option value="alat_lab" {{ ($filters['kategori'] ?? '') == 'alat_lab' ? 'selected' : '' }}>Alat Lab</option>
                        <option value="buku" {{ ($filters['kategori'] ?? '') == 'buku' ? 'selected' : '' }}>Buku</option>
                        <option value="perlengkapan" {{ ($filters['kategori'] ?? '') == 'perlengkapan' ? 'selected' : '' }}>Perlengkapan</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label form-label-custom">Status</label>
                    <select name="status" class="form-select form-control-custom">
                        <option value="">Semua Status</option>
                        <option value="tersedia" {{ ($filters['status'] ?? '') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="dipinjam" {{ ($filters['status'] ?? '') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="rusak" {{ ($filters['status'] ?? '') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                        <option value="maintenance" {{ ($filters['status'] ?? '') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label form-label-custom">Kondisi</label>
                    <select name="kondisi" class="form-select form-control-custom">
                        <option value="">Semua Kondisi</option>
                        <option value="baik" {{ ($filters['kondisi'] ?? '') == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak_ringan" {{ ($filters['kondisi'] ?? '') == 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="rusak_berat" {{ ($filters['kondisi'] ?? '') == 'rusak_berat' ? 'selected' : '' }}>Rusak Berat</option>
                    </select>
                </div>
                
                <!-- Filter Row 2
                <div class="col-md-3">
                    <label class="form-label form-label-custom">Tahun Pengadaan</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="number" name="tahun_min" class="form-control form-control-custom" 
                                   placeholder="Dari" min="2000" max="{{ date('Y') }}"
                                   value="{{ $filters['tahun_min'] ?? '' }}">
                        </div>
                        <div class="col-6">
                            <input type="number" name="tahun_max" class="form-control form-control-custom" 
                                   placeholder="Sampai" min="2000" max="{{ date('Y') }}"
                                   value="{{ $filters['tahun_max'] ?? '' }}">
                        </div>
                    </div>
                </div> -->
                
                <!-- Filter Row 3 -->
                <div class="col-md-3">
                    <label class="form-label form-label-custom">Urutkan Berdasarkan</label>
                    <select name="sort" class="form-select form-control-custom">
                        <option value="created_at" {{ ($filters['sort'] ?? 'created_at') == 'created_at' ? 'selected' : '' }}>Tanggal Input</option>
                        <option value="kode_barang" {{ ($filters['sort'] ?? '') == 'kode_barang' ? 'selected' : '' }}>Kode Barang</option>
                        <option value="nama_barang" {{ ($filters['sort'] ?? '') == 'nama_barang' ? 'selected' : '' }}>Nama Barang</option>
                        <option value="tahun_pengadaan" {{ ($filters['sort'] ?? '') == 'tahun_pengadaan' ? 'selected' : '' }}>Tahun Pengadaan</option>
                    </select>
                </div>
                
                <!-- <div class="col-md-2">
                    <label class="form-label form-label-custom">Urutan</label>
                    <select name="order" class="form-select form-control-custom">
                        <option value="desc" {{ ($filters['order'] ?? 'desc') == 'desc' ? 'selected' : '' }}>Descending</option>
                        <option value="asc" {{ ($filters['order'] ?? '') == 'asc' ? 'selected' : '' }}>Ascending</option>
                    </select>
                </div> -->
                
                <!-- <div class="col-md-2">
                    <label class="form-label form-label-custom">Item per Halaman</label>
                    <select name="per_page" class="form-select form-control-custom">
                        <option value="10" {{ ($filters['per_page'] ?? 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ ($filters['per_page'] ?? '') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ ($filters['per_page'] ?? '') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ ($filters['per_page'] ?? '') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div> -->
                
                <!-- Tombol Aksi Filter -->
                <div class="col-md-5 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-custom btn-custom-primary flex-fill">
                        <i class="bi bi-filter me-1"></i> Terapkan Filter
                    </button>
                    <a href="{{ route('barang.index') }}" class="btn btn-custom btn-custom-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i> Reset
                    </a>
                    <button type="button" class="btn btn-custom btn-custom-info" id="exportBtn">
                        <i class="bi bi-download me-1"></i> Export
                    </button>
                </div>
            </div>
        </form>
        
        <!-- Quick Stats -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-light text-dark">
                        <i class="bi bi-box me-1"></i> Total: {{ $stats['total'] }}
                    </span>
                    <span class="badge bg-success">
                        <i class="bi bi-check-circle me-1"></i> Tersedia: {{ $stats['tersedia'] }}
                    </span>
                    <span class="badge bg-warning">
                        <i class="bi bi-clock me-1"></i> Dipinjam: {{ $stats['dipinjam'] }}
                    </span>
                    <span class="badge bg-danger">
                        <i class="bi bi-tools me-1"></i> Perlu Perbaikan: {{ $stats['rusak'] }}
                    </span>
                    <span class="badge bg-primary">
                        <i class="bi bi-cpu me-1"></i> Elektronik: {{ $stats['elektronik'] }}
                    </span>
                    <span class="badge bg-info">
                        <i class="bi bi-tools me-1"></i> Alat Lab: {{ $stats['alat_lab'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Tabel Barang -->
    <div class="card card-custom">
        <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i> Daftar Barang
            </h5>
            <span class="badge bg-primary">
                Total: {{ $barang->total() }} barang
            </span>
        </div>
        <div class="card-body">
            @if($barang->isEmpty())
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-box display-6 d-block mb-3"></i>
                    <h5>Belum ada data barang</h5>
                    <p>Mulai dengan menambahkan barang pertama Anda</p>
                    <a href="{{ route('barang.create') }}" class="btn btn-custom btn-custom-primary mt-3">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Barang Pertama
                    </a>
                </div>
            </div>
            @else
            <div class="table-responsive">
                <table class="table table-custom">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Kondisi</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barang as $index => $item)
                        <tr>
                            <td>{{ ($barang->currentPage() - 1) * $barang->perPage() + $loop->iteration }}</td>
                            <td>
                                <strong class="text-primary">{{ $item->kode_barang }}</strong>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $item->nama_barang }}</div>
                                @if($item->merek)
                                <small class="text-muted">{{ $item->merek }}</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $kategoriColors = [
                                        'elektronik' => 'primary',
                                        'alat_lab' => 'success',
                                        'buku' => 'info',
                                        'perlengkapan' => 'warning'
                                    ];
                                @endphp
                                <span class="badge badge-custom bg-{{ $kategoriColors[$item->kategori] ?? 'secondary' }}">
                                    {{ ucfirst(str_replace('_', ' ', $item->kategori)) }}
                                </span>
                            </td>
                            <td>{{ $item->lokasi }}</td>
                            <td>
                                <span class="badge badge-custom bg-{{ $item->status_badge }}">
                                    {{ ucfirst($item->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge badge-custom bg-{{ $item->kondisi_badge }}">
                                    {{ ucfirst(str_replace('_', ' ', $item->kondisi)) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-action-group">
                                    <!-- Tombol Detail -->
                                    <a href="{{ route('barang.show', $item->id) }}" 
                                       class="btn-action btn-custom-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    <!-- Tombol Edit -->
                                    <a href="{{ route('barang.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
        <i class="fas fa-eye"></i>
    </a>
    <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
        <i class="fas fa-edit"></i>
    </a>
                                    
                                    <!-- Tombol Hapus -->
                                    @if($item->status != 'dipinjam')
                                    <form action="{{ route('barang.destroy', $item->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn-action btn-custom-danger" 
                                                title="Hapus"
                                                onclick="return confirm('Hapus barang {{ $item->nama_barang }}?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Menampilkan {{ $barang->firstItem() ?? 0 }} - {{ $barang->lastItem() ?? 0 }} dari {{ $barang->total() }} barang
                </div>
                <div>
                    {{ $barang->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Tambahkan CSS untuk tombol action -->
<style>
    .btn-custom-info {
        background: var(--info-color);
        color: white;
    }
    
    .btn-custom-info:hover {
        background: #138496;
        color: white;
    }
    
    .btn-custom-warning {
        background: var(--warning-color);
        color: white;
    }
    
    .btn-custom-warning:hover {
        background: #e0a800;
        color: white;
    }
    
    .btn-custom-danger {
        background: var(--danger-color);
        color: white;
    }
    
    .btn-custom-danger:hover {
        background: #c82333;
        color: white;
    }
</style>
@endsection