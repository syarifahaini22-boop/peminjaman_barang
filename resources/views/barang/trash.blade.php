@extends('layouts.app')

@section('title', 'Tong Sampah Barang')
@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="navbar-custom mb-4">
        <div>
            <h2 class="h3 mb-1">
                <i class="bi bi-trash me-2"></i> Tong Sampah Barang
            </h2>
            <p class="text-muted mb-0">Barang yang telah dihapus (soft delete)</p>
        </div>
        <div class="btn-group">
            <a href="{{ route('barang.index') }}" class="btn btn-custom btn-custom-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Data Barang
            </a>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Info Card -->
    <div class="card card-custom mb-4">
        <div class="card-body bg-light">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle fs-4 text-primary me-3"></i>
                <div>
                    <h6 class="mb-1">Informasi Tong Sampah</h6>
                    <p class="mb-0 text-muted">
                        Barang yang dihapus akan tetap tersimpan di tong sampah selama 30 hari sebelum dihapus permanen secara otomatis.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="card card-custom mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h6 class="mb-0">Aksi Massal</h6>
                    <small class="text-muted">Kelola semua barang di tong sampah</small>
                </div>
                <div class="btn-group">
                    <form action="{{ route('barang.restore-all') }}" method="POST" class="d-inline me-2">
                        @csrf
                        <button type="submit" class="btn btn-custom btn-custom-success" 
                                onclick="return confirm('Pulihkan SEMUA barang dari tong sampah?')">
                            <i class="bi bi-arrow-clockwise me-1"></i> Pulihkan Semua
                        </button>
                    </form>
                    <form action="{{ route('barang.empty-trash') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-custom btn-custom-danger" 
                                onclick="return confirm('Hapus PERMANEN SEMUA barang di tong sampah? Tindakan ini tidak dapat dibatalkan!')">
                            <i class="bi bi-trash-fill me-1"></i> Kosongkan Tong Sampah
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Barang Terhapus -->
    <div class="card card-custom">
        <div class="card-header card-header-custom d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-list-ul me-2"></i> Daftar Barang Terhapus
            </h5>
            <span class="badge bg-danger">
                Total: {{ $trashed->total() }} barang
            </span>
        </div>
        <div class="card-body">
            @if($trashed->isEmpty())
            <div class="text-center py-5">
                <div class="text-muted">
                    <i class="bi bi-trash display-6 d-block mb-3"></i>
                    <h5>Tong sampah kosong</h5>
                    <p>Tidak ada barang yang dihapus</p>
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
                            <th>Dihapus Pada</th>
                            <th>Alasan Penghapusan</th>
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trashed as $index => $item)
                        <tr>
                            <td>{{ ($trashed->currentPage() - 1) * $trashed->perPage() + $loop->iteration }}</td>
                            <td>
                                <strong class="text-muted">{{ $item->kode_barang }}</strong>
                            </td>
                            <td>
                                <div class="fw-medium">{{ $item->nama_barang }}</div>
                                <small class="text-muted">{{ $item->merek ?? '-' }}</small>
                            </td>
                            <td>
                                <span class="badge badge-custom bg-secondary">
                                    {{ ucfirst(str_replace('_', ' ', $item->kategori)) }}
                                </span>
                            </td>
                            <td>
                                {{ $item->deleted_at->format('d/m/Y H:i') }}
                                <br>
                                <small class="text-muted">
                                    {{ $item->deleted_at->diffForHumans() }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    Soft Delete
                                </span>
                            </td>
                            <td>
                                <div class="btn-action-group">
                                    <!-- Tombol Restore -->
                                    <form action="{{ route('barang.restore', $item->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" 
                                                class="btn-action btn-custom-success" 
                                                title="Pulihkan"
                                                onclick="return confirm('Pulihkan barang {{ $item->nama_barang }}?')">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    </form>
                                    
                                    <!-- Tombol Hapus Permanen -->
                                    <form action="{{ route('barang.force-delete', $item->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn-action btn-custom-danger" 
                                                title="Hapus Permanen"
                                                onclick="return confirm('Hapus PERMANEN barang {{ $item->nama_barang }}? Tindakan ini tidak dapat dibatalkan!')">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                    
                                    <!-- Tombol Detail -->
                                    <a href="#" 
                                       class="btn-action btn-custom-info" 
                                       title="Detail"
                                       data-bs-toggle="modal" 
                                       data-bs-target="#detailModal{{ $item->id }}">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </div>
                                
                                <!-- Modal Detail -->
                                <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Detail Barang Terhapus</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-borderless">
                                                    <tr>
                                                        <th width="40%">Kode Barang</th>
                                                        <td>{{ $item->kode_barang }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Nama Barang</th>
                                                        <td>{{ $item->nama_barang }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Kategori</th>
                                                        <td>{{ ucfirst($item->kategori) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Merek</th>
                                                        <td>{{ $item->merek ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Status Terakhir</th>
                                                        <td>{{ ucfirst($item->status) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Kondisi Terakhir</th>
                                                        <td>{{ ucfirst($item->kondisi) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Dibuat Pada</th>
                                                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Dihapus Pada</th>
                                                        <td>{{ $item->deleted_at->format('d/m/Y H:i') }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
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
                    Menampilkan {{ $trashed->firstItem() ?? 0 }} - {{ $trashed->lastItem() ?? 0 }} dari {{ $trashed->total() }} barang terhapus
                </div>
                <div>
                    {{ $trashed->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Style khusus untuk halaman trash */
    .table tbody tr {
        background-color: #f8f9fa;
    }
    
    .table tbody tr:hover {
        background-color: #e9ecef;
    }
</style>
@endsection