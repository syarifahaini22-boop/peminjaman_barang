@extends('layouts.app')

@section('title', 'Laporan Barang')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">
                <i class="fas fa-box"></i> Laporan Barang
            </h1>

        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('laporan.barang') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Kategori</label>
                            <select name="kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach ($stats['kategori'] as $kategori)
                                    <option value="{{ $kategori->kategori }}"
                                        {{ $kategori == $kategori->kategori ? 'selected' : '' }}>
                                        {{ $kategori->kategori }} ({{ $kategori->total }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Urutkan Berdasarkan</label>
                            <select class="form-select" onchange="this.form.submit()">
                                <option value="nama">Nama Barang</option>
                                <option value="stok">Stok Terbanyak</option>
                                <option value="peminjaman">Paling Sering Dipinjam</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h6 class="card-title">Total Barang</h6>
                        <h3 class="card-text">{{ $stats['total_barang'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h6 class="card-title">Total Stok</h6>
                        <h3 class="card-text">{{ $stats['total_stok'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h6 class="card-title">Sedang Dipinjam</h6>
                        <h3 class="card-text">{{ $stats['total_dipinjam'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h6 class="card-title">Stok Tersedia</h6>
                        <h3 class="card-text">{{ $stats['total_stok'] - $stats['total_dipinjam'] }}</h3>
                    </div>
                </div>
            </div>
        </div>



        <!-- Barang Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    Daftar Barang
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Dipinjam</th>
                                <th>Tersedia</th>
                                <th>Total Peminjaman</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($barang as $index => $item)
                                @php
                                    $tersedia = $item->stok - $item->total_dipinjam;
                                    $status = $tersedia > 0 ? ($tersedia >= 5 ? 'tersedia' : 'hampir habis') : 'habis';
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $item->kode_barang }}</strong></td>
                                    <td>{{ $item->nama }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $item->kategori }}</span>
                                    </td>
                                    <td class="text-center">{{ $item->stok }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-warning">{{ $item->total_dipinjam }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span
                                            class="badge bg-{{ $status == 'tersedia' ? 'success' : ($status == 'hampir habis' ? 'warning' : 'danger') }}">
                                            {{ $tersedia }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">{{ $item->total_peminjaman }}</span>
                                    </td>
                                    <td>
                                        @if ($status == 'tersedia')
                                            <span class="badge bg-success">Tersedia</span>
                                        @elseif($status == 'hampir habis')
                                            <span class="badge bg-warning">Hampir Habis</span>
                                        @else
                                            <span class="badge bg-danger">Habis</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Modal -->
    <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exportModalLabel">
                        <i class="fas fa-download"></i> Export Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-file-excel text-success me-2"></i>
                            Export ke Excel (.xlsx)
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-file-csv text-primary me-2"></i>
                            Export ke CSV (.csv)
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <i class="fas fa-file-pdf text-danger me-2"></i>
                            Export ke PDF (.pdf)
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            $(document).ready(function() {
                // Data untuk chart kategori
                const kategoriData = {
                    labels: {!! json_encode($stats['kategori']->pluck('kategori')) !!},
                    datasets: [{
                        data: {!! json_encode($stats['kategori']->pluck('total')) !!},
                        backgroundColor: [
                            '#0d6efd', '#198754', '#ffc107', '#dc3545', '#6c757d',
                            '#0dcaf0', '#6610f2', '#fd7e14', '#20c997', '#ff6b6b'
                        ]
                    }]
                };

                // Chart Kategori
                const kategoriCtx = document.getElementById('kategoriChart').getContext('2d');
                new Chart(kategoriCtx, {
                    type: 'doughnut',
                    data: kategoriData,
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'right'
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.raw || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = Math.round((value / total) * 100);
                                        return `${label}: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
