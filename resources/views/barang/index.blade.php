@extends('layouts.app')

@section('title', 'Data Barang')
@section('content')
    <div class="container-fluid">
        <!-- Page Header dengan Tombol Tambah -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>

            </div>
            <a href="{{ route('peminjaman.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Barang
            </a>
        </div>

        <!-- Alert Notifikasi -->
        @if (session('success'))
            <div class="alert alert-success alert-custom alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
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
                        <!-- Search Input dengan auto-search -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label form-label-custom">Pencarian</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" name="search" class="form-control form-control-custom"
                                    placeholder="Cari berdasarkan kode, nama, atau lokasi..."
                                    value="{{ $filters['search'] ?? '' }}" id="searchInput" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @push('scripts')
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('searchInput');
                    const filterForm = document.getElementById('filterForm');
                    const tableBody = document.querySelector('tbody');
                    const originalTableHTML = tableBody.innerHTML; // Simpan data awal
                    let searchTimeout;

                    // Fungsi untuk AJAX search
                    function performSearch() {
                        const searchValue = searchInput.value.trim();

                        // Jika kosong, tampilkan data awal
                        if (searchValue === '') {
                            tableBody.innerHTML = originalTableHTML;
                            return;
                        }

                        // Show loading state
                        tableBody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-4">
                    <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                    Mencari "${searchValue}"...
                </td>
            </tr>
        `;

                        // AJAX request
                        fetch(`{{ route('barang.index') }}?search=${encodeURIComponent(searchValue)}&ajax=1`)
                            .then(response => response.text())
                            .then(html => {
                                // Parse HTML response
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(html, 'text/html');
                                const newTableBody = doc.querySelector('tbody');

                                if (newTableBody) {
                                    tableBody.innerHTML = newTableBody.innerHTML;

                                    // Update pagination jika ada
                                    const pagination = doc.querySelector('.pagination');
                                    const currentPagination = document.querySelector('.pagination');
                                    if (pagination && currentPagination) {
                                        currentPagination.innerHTML = pagination.innerHTML;
                                    }

                                    // Update stats jika ada
                                    const statsContainer = doc.querySelector('.quick-stats');
                                    const currentStats = document.querySelector('.quick-stats');
                                    if (statsContainer && currentStats) {
                                        currentStats.innerHTML = statsContainer.innerHTML;
                                    }
                                }
                            })
                            .catch(error => {
                                console.error('Search error:', error);
                                tableBody.innerHTML = `
                    <tr>
                        <td colspan="8" class="text-center py-4 text-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Gagal memuat data. Silakan coba lagi.
                        </td>
                    </tr>
                `;
                            });
                    }

                    // Auto-search saat ketik (tanpa delay yang mengganggu)
                    searchInput.addEventListener('input', function() {
                        clearTimeout(searchTimeout);

                        // Minimal 2 karakter atau kosong untuk search
                        if (this.value.length >= 2 || this.value.length === 0) {
                            // Submit langsung tanpa delay
                            performSearch();
                        } else if (this.value.length === 1) {
                            // Tunggu sedikit untuk karakter kedua
                            searchTimeout = setTimeout(performSearch, 300);
                        }
                    });

                    // Enter key untuk submit form normal (jika perlu)
                    searchInput.addEventListener('keypress', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            clearTimeout(searchTimeout);
                            filterForm.submit(); // Submit normal untuk filter lainnya
                        }
                    });

                    // Optional: Tambahkan tombol clear search
                    const clearButton = document.createElement('button');
                    clearButton.type = 'button';
                    clearButton.className = 'btn btn-sm btn-outline-secondary position-absolute';
                    clearButton.style.right = '10px';
                    clearButton.style.top = '50%';
                    clearButton.style.transform = 'translateY(-50%)';
                    clearButton.style.zIndex = '5';
                    clearButton.innerHTML = '<i class="bi bi-x"></i>';
                    clearButton.title = 'Hapus pencarian';

                    clearButton.addEventListener('click', function() {
                        searchInput.value = '';
                        searchInput.focus();
                        performSearch();
                    });

                    // Sembunyikan tombol clear jika search kosong
                    searchInput.addEventListener('input', function() {
                        if (this.value) {
                            clearButton.style.display = 'block';
                        } else {
                            clearButton.style.display = 'none';
                        }
                    });

                    // Tambahkan tombol clear ke input group
                    searchInput.parentElement.style.position = 'relative';
                    searchInput.parentElement.appendChild(clearButton);
                });
            </script>

            <style>
                .input-group {
                    position: relative;
                }

                /* Style untuk tombol clear */
                .input-group .btn-outline-secondary {
                    display: none;
                    padding: 0.25rem 0.5rem;
                    font-size: 0.875rem;
                }

                /* Pastikan input tidak tertutup tombol */
                .form-control {
                    padding-right: 40px !important;
                }
            </style>
        @endpush

        <!-- Tambahkan CSS untuk posisi spinner -->
        <style>
            .input-group {
                position: relative;
            }

            #searchSpinner {
                position: absolute;
                right: 60px;
                top: 50%;
                transform: translateY(-50%);
                z-index: 10;
            }
        </style>

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
                @if ($barang->isEmpty())
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
                                @foreach ($barang as $index => $item)
                                    <tr>
                                        <td>{{ ($barang->currentPage() - 1) * $barang->perPage() + $loop->iteration }}</td>
                                        <td>
                                            <strong class="text-primary">{{ $item->kode_barang }}</strong>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ $item->nama }}</div>
                                            @if ($item->merek)
                                                <small class="text-muted">{{ $item->merek }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $kategoriColors = [
                                                    'elektronik' => 'primary',
                                                    'alat_lab' => 'success',
                                                    'buku' => 'info',
                                                    'perlengkapan' => 'warning',
                                                ];
                                            @endphp
                                            <span
                                                class="badge badge-custom bg-{{ $kategoriColors[$item->kategori] ?? 'secondary' }}">
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
                                                <a href="{{ route('barang.edit', $item->id) }}"
                                                    class="btn-action btn-custom-warning" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>

                                                <!-- Tombol Hapus -->
                                                @if ($item->status != 'dipinjam')
                                                    <form action="{{ route('barang.destroy', $item->id) }}" method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn-action btn-custom-danger"
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
                            Menampilkan {{ $barang->firstItem() ?? 0 }} - {{ $barang->lastItem() ?? 0 }} dari
                            {{ $barang->total() }} barang
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
