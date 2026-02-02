@extends('layouts.app')

@section('title', 'Laporan Peminjaman')

@section('content')
    <div class="container-fluid">
        <!-- Search Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('laporan.peminjaman') }}" id="searchForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-8">
                            <label class="form-label">Cari berdasarkan nama barang, mahasiswa, atau kode peminjaman</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" name="search" class="form-control"
                                    placeholder="Masukkan nama barang, mahasiswa, atau kode peminjaman..."
                                    value="{{ $search ?? '' }}" id="searchInput" autocomplete="off">
                                @if (!empty($search))
                                    <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                                        <i class="fas fa-times"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-grid gap-2 d-md-flex">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search"></i> Cari
                                </button>
                                @if (!empty($search))
                                    <a href="{{ route('laporan.peminjaman') }}" class="btn btn-secondary">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <!-- Laporan Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    Detail Peminjaman
                    @if (!empty($search))
                        <small class="text-muted">(Hasil pencarian: "{{ $search }}")</small>
                    @endif
                </h5>
                @if (!empty($search))
                    <div class="text-muted">
                        Ditemukan {{ $peminjaman->total() }} data
                    </div>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="50">No</th>
                                <th>Kode</th>
                                <th>Barang</th>
                                <th>Mahasiswa</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjaman as $index => $item)
                                <tr>
                                    <td class="text-center">
                                        {{ ($peminjaman->currentPage() - 1) * $peminjaman->perPage() + $index + 1 }}</td>
                                    <td><strong>{{ $item->kode_peminjaman }}</strong></td>
                                    <td>
                                        @foreach ($item->barang as $barang)
                                            <div>• {{ $barang->nama }}
                                                @if ($barang->pivot->jumlah > 1)
                                                    <span class="badge bg-secondary">x{{ $barang->pivot->jumlah }}</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </td>
                                    <td>{{ $item->mahasiswa->name }}</td>
                                    <td>{{ $item->tanggal_peminjaman->format('d/m/Y') }}</td>
                                    <td>{{ $item->tanggal_pengembalian->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        @if ($item->status == 'dipinjam')
                                            <span class="badge bg-warning">Dipinjam</span>
                                        @elseif($item->status == 'dikembalikan')
                                            <span class="badge bg-success">Dikembalikan</span>
                                        @else
                                            <span class="badge bg-danger">Terlambat</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        @if (!empty($search))
                                            <i class="fas fa-search fa-2x text-muted mb-3"></i>
                                            <p class="mb-1">Tidak ditemukan data peminjaman untuk "{{ $search }}"
                                            </p>
                                            <small class="text-muted">Coba dengan kata kunci lain</small>
                                        @else
                                            <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                                            <p class="mb-1">Belum ada data peminjaman</p>
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    @if ($peminjaman->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Menampilkan {{ $peminjaman->firstItem() }} - {{ $peminjaman->lastItem() }} dari
                                {{ $peminjaman->total() }} data
                            </div>
                            <nav>
                                {{ $peminjaman->withQueryString()->links() }}
                            </nav>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {

            .sidebar,
            .card-header .btn-group,
            .filter-card,
            .stats-cards,
            #searchForm,
            .pagination {
                display: none !important;
            }

            .main-content {
                margin-left: 0 !important;
            }

            .card {
                border: none !important;
            }

            .card-body {
                padding: 0 !important;
            }

            table {
                font-size: 11px !important;
            }

            .badge {
                border: 1px solid #000 !important;
                color: #000 !important;
                background-color: transparent !important;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const clearSearchBtn = document.getElementById('clearSearch');
            const searchForm = document.getElementById('searchForm');

            // Clear search button
            if (clearSearchBtn) {
                clearSearchBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    searchForm.submit();
                });
            }

            // Submit form on Enter key
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    searchForm.submit();
                }
            });

            // Focus on search input when page loads
            if (searchInput) {
                searchInput.focus();

                // Place cursor at the end of text
                const value = searchInput.value;
                searchInput.value = '';
                searchInput.value = value;
            }
        });
    </script>
@endsection
