@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    <div class="container-fluid px-4 py-3">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">


        </div>



        <!-- Laporan Menu Grid -->
        <div class="row">
            <!-- Peminjaman Card -->
            <div class="col-xl-4 col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                        <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-hand-holding text-primary fs-5"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Laporan Peminjaman</h5>
                            <small class="text-muted">Detail transaksi peminjaman</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text text-muted">Analisis lengkap data peminjaman barang dengan berbagai filter dan
                            statistik.</p>
                        <div class="mb-3">
                            <span class="badge bg-primary bg-opacity-10 text-primary me-2">Filter Tanggal</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary me-2">Filter Status</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary">Analisis Grafik</span>
                        </div>
                        <div class="d-flex align-items-center text-muted mb-3">
                            <i class="fas fa-chart-bar me-2"></i>
                            <small>Statistik lengkap dengan visualisasi data</small>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <a href="{{ route('laporan.peminjaman') }}" class="btn btn-primary w-100">
                            <i class="fas fa-arrow-right me-2"></i>Akses Laporan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Pengembalian Card -->
            <div class="col-xl-4 col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                        <div class="icon-wrapper bg-success bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-undo text-success fs-5"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Laporan Pengembalian</h5>
                            <small class="text-muted">Analisis keterlambatan & kinerja</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text text-muted">Monitoring pengembalian barang dan analisis tingkat keterlambatan.
                        </p>
                        <div class="mb-3">
                            <span class="badge bg-success bg-opacity-10 text-success me-2">Data Pengembalian</span>
                            <span class="badge bg-success bg-opacity-10 text-success me-2">Analisis Terlambat</span>
                            <span class="badge bg-success bg-opacity-10 text-success">Persentase</span>
                        </div>
                        <div class="d-flex align-items-center text-muted mb-3">
                            <i class="fas fa-clock me-2"></i>
                            <small>Monitoring ketepatan waktu pengembalian</small>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <a href="{{ route('laporan.pengembalian') }}" class="btn btn-success w-100">
                            <i class="fas fa-arrow-right me-2"></i>Akses Laporan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Barang Card -->
            <div class="col-xl-4 col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                        <div class="icon-wrapper bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-box text-warning fs-5"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Laporan Barang</h5>
                            <small class="text-muted">Inventori & tingkat pemakaian</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text text-muted">Analisis data barang, stok, dan tingkat peminjamannya.</p>
                        <div class="mb-3">
                            <span class="badge bg-warning bg-opacity-10 text-warning me-2">Stok vs Dipinjam</span>
                            <span class="badge bg-warning bg-opacity-10 text-warning me-2">Kategori Barang</span>
                            <span class="badge bg-warning bg-opacity-10 text-warning">Barang Populer</span>
                        </div>
                        <div class="d-flex align-items-center text-muted mb-3">
                            <i class="fas fa-star me-2"></i>
                            <small>Identifikasi barang paling sering dipinjam</small>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <a href="{{ route('laporan.barang') }}" class="btn btn-warning w-100">
                            <i class="fas fa-arrow-right me-2"></i>Akses Laporan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mahasiswa Card -->
            <div class="col-xl-4 col-lg-6 mb-4">
                <div class="card border-0 shadow-sm h-100 hover-card">
                    <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                        <div class="icon-wrapper bg-info bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-users text-info fs-5"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Laporan Mahasiswa</h5>
                            <small class="text-muted">Aktivitas & riwayat peminjaman</small>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="card-text text-muted">Analisis aktivitas peminjaman berdasarkan mahasiswa dan jurusan.
                        </p>
                        <div class="mb-3">
                            <span class="badge bg-info bg-opacity-10 text-info me-2">Mahasiswa Aktif</span>
                            <span class="badge bg-info bg-opacity-10 text-info me-2">Riwayat Peminjaman</span>
                            <span class="badge bg-info bg-opacity-10 text-info">Analisis Jurusan</span>
                        </div>
                        <div class="d-flex align-items-center text-muted mb-3">
                            <i class="fas fa-graduation-cap me-2"></i>
                            <small>Statistik per jurusan dan angkatan</small>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 pt-0">
                        <a href="{{ route('laporan.mahasiswa') }}" class="btn btn-info w-100">
                            <i class="fas fa-arrow-right me-2"></i>Akses Laporan
                        </a>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- Modal Statistik -->
    <div class="modal fade" id="statistikModal" tabindex="-1" aria-labelledby="statistikModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title" id="statistikModalLabel">
                        <i class="fas fa-chart-line me-2"></i>Dashboard Statistik Lengkap
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Tab Navigation -->
                    <ul class="nav nav-tabs mb-4" id="statTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="peminjaman-tab" data-bs-toggle="tab"
                                data-bs-target="#peminjaman" type="button">
                                <i class="fas fa-hand-holding me-2"></i>Peminjaman
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="barang-tab" data-bs-toggle="tab" data-bs-target="#barang"
                                type="button">
                                <i class="fas fa-box me-2"></i>Barang
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="mahasiswa-tab" data-bs-toggle="tab" data-bs-target="#mahasiswa"
                                type="button">
                                <i class="fas fa-users me-2"></i>Mahasiswa
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="statTabContent">
                        <!-- Peminjaman Tab -->
                        <div class="tab-pane fade show active" id="peminjaman" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-white border-0">
                                            <h6 class="fw-bold mb-0">Peminjaman Per Bulan ({{ date('Y') }})</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="monthlyChart" height="250"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-white border-0">
                                            <h6 class="fw-bold mb-0">Status Peminjaman</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="statusChart" height="250"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Barang Tab -->
                        <div class="tab-pane fade" id="barang" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-white border-0">
                                            <h6 class="fw-bold mb-0">Kategori Barang</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="categoryChart" height="250"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-white border-0">
                                            <h6 class="fw-bold mb-0">Top 5 Barang Paling Sering Dipinjam</h6>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $topBarang = App\Models\Barang::withCount([
                                                    'peminjaman as peminjaman_count',
                                                ])
                                                    ->orderBy('peminjaman_count', 'desc')
                                                    ->limit(5)
                                                    ->get();
                                            @endphp
                                            <div class="list-group list-group-flush">
                                                @foreach ($topBarang as $index => $barang)
                                                    <div class="list-group-item border-0 px-0">
                                                        <div class="d-flex align-items-center">
                                                            <div class="rank-circle bg-primary text-white fw-bold me-3">
                                                                {{ $index + 1 }}</div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-1">{{ $barang->nama }}</h6>
                                                                <small class="text-muted">{{ $barang->kategori }}</small>
                                                            </div>
                                                            <div class="text-end">
                                                                <div class="fw-bold text-primary">
                                                                    {{ $barang->peminjaman_count }} kali</div>
                                                                <div class="progress" style="height: 4px; width: 100px;">
                                                                    <div class="progress-bar bg-primary"
                                                                        style="width: {{ min($barang->peminjaman_count * 20, 100) }}%">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mahasiswa Tab -->
                        <div class="tab-pane fade" id="mahasiswa" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-white border-0">
                                            <h6 class="fw-bold mb-0">Distribusi Per Jurusan</h6>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="jurusanChart" height="250"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-header bg-white border-0">
                                            <h6 class="fw-bold mb-0">Top 5 Mahasiswa Paling Aktif</h6>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                $topMahasiswa = App\Models\User::where('role', 'mahasiswa')
                                                    ->withCount(['peminjaman as peminjaman_count'])
                                                    ->orderBy('peminjaman_count', 'desc')
                                                    ->limit(5)
                                                    ->get();
                                            @endphp
                                            <div class="list-group list-group-flush">
                                                @foreach ($topMahasiswa as $index => $mahasiswa)
                                                    <div class="list-group-item border-0 px-0">
                                                        <div class="d-flex align-items-center">
                                                            <div
                                                                class="avatar-circle-sm bg-success bg-opacity-10 text-success me-3">
                                                                <span>{{ substr($mahasiswa->name, 0, 2) }}</span>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="mb-1">{{ $mahasiswa->name }}</h6>
                                                                <small class="text-muted">{{ $mahasiswa->nim }} |
                                                                    {{ $mahasiswa->jurusan }}</small>
                                                            </div>
                                                            <div class="text-end">
                                                                <div class="fw-bold text-success">
                                                                    {{ $mahasiswa->peminjaman_count }} kali</div>
                                                                <small
                                                                    class="text-muted">{{ round(($mahasiswa->peminjaman_count / max($topMahasiswa[0]->peminjaman_count, 1)) * 100, 0) }}%
                                                                    dari tertinggi</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .stat-card {
            border-left: 4px solid;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.primary {
            border-left-color: #4e73df;
        }

        .stat-card.success {
            border-left-color: #1cc88a;
        }

        .stat-card.warning {
            border-left-color: #f6c23e;
        }

        .stat-card.info {
            border-left-color: #36b9cc;
        }

        .hover-card {
            transition: all 0.3s ease;
            border: 1px solid transparent;
        }

        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
            border-color: rgba(78, 115, 223, 0.2);
        }

        .icon-wrapper {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar-circle-sm {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .rank-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 12px 20px;
            border-radius: 8px 8px 0 0;
        }

        .nav-tabs .nav-link.active {
            color: #4e73df;
            background-color: rgba(78, 115, 223, 0.1);
            border-bottom: 3px solid #4e73df;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1rem !important;
            }

            .icon-wrapper {
                width: 40px;
                height: 40px;
            }
        }
    </style>
@endpush

@push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Mini Trend Chart
            const trendCtx = document.getElementById('miniTrendChart').getContext('2d');
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei'],
                    datasets: [{
                        label: 'Peminjaman',
                        data: [12, 19, 15, 25, 22],
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            display: false
                        },
                        x: {
                            display: false
                        }
                    }
                }
            });

            // Mini Status Chart
            const statusMiniCtx = document.getElementById('miniStatusChart').getContext('2d');
            new Chart(statusMiniCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Dipinjam', 'Dikembalikan', 'Terlambat'],
                    datasets: [{
                        data: [8, 15, 2],
                        backgroundColor: ['#f6c23e', '#1cc88a', '#e74a3b']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    cutout: '70%'
                }
            });

            // Target Chart
            const targetCtx = document.getElementById('targetChart').getContext('2d');
            new Chart(targetCtx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [{{ App\Models\Peminjaman::whereMonth('tanggal_peminjaman', date('m'))->count() }},
                            50 -
                            {{ App\Models\Peminjaman::whereMonth('tanggal_peminjaman', date('m'))->count() }}
                        ],
                        backgroundColor: ['#4e73df', '#e3e6f0']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    cutout: '70%'
                }
            });

            // Monthly Chart in Modal
            const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
            const monthlyChart = new Chart(monthlyCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov',
                        'Des'
                    ],
                    datasets: [{
                        label: 'Jumlah Peminjaman',
                        data: [12, 19, 15, 25, 22, 30, 28, 35, 32, 40, 38, 45],
                        backgroundColor: 'rgba(78, 115, 223, 0.8)',
                        borderColor: '#4e73df',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 5
                            }
                        }
                    }
                }
            });

            // Status Chart in Modal
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'pie',
                data: {
                    labels: ['Dipinjam', 'Dikembalikan', 'Terlambat', 'Hilang/Rusak'],
                    datasets: [{
                        data: [8, 32, 2, 1],
                        backgroundColor: ['#f6c23e', '#1cc88a', '#e74a3b', '#6c757d']
                    }]
                }
            });

            // Category Chart
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'polarArea',
                data: {
                    labels: ['Elektronik', 'Buku', 'Alat Lab', 'Furniture', 'Lainnya'],
                    datasets: [{
                        data: [15, 25, 10, 8, 5],
                        backgroundColor: [
                            '#4e73df', '#1cc88a', '#f6c23e', '#e74a3b', '#6c757d'
                        ]
                    }]
                }
            });

            // Jurusan Chart
            const jurusanCtx = document.getElementById('jurusanChart').getContext('2d');
            new Chart(jurusanCtx, {
                type: 'bar',
                data: {
                    labels: ['Teknik Informatika', 'Sistem Informasi', 'Teknik Elektro', 'Manajemen',
                        'Akuntansi'
                    ],
                    datasets: [{
                        label: 'Jumlah Mahasiswa',
                        data: [45, 32, 28, 22, 18],
                        backgroundColor: 'rgba(28, 200, 138, 0.8)',
                        borderColor: '#1cc88a',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });

        function printDashboard() {
            const modalContent = document.querySelector('#statistikModal .modal-content');
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
        <html>
            <head>
                <title>Dashboard Statistik - Sistem Peminjaman Lab RSI</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { padding: 20px; }
                    .card { margin-bottom: 20px; }
                    @media print {
                        .no-print { display: none; }
                    }
                </style>
            </head>
            <body>
                <h3>Dashboard Statistik - {{ date('d F Y') }}</h3>
                ${modalContent.innerHTML}
            </body>
        </html>
    `);
            printWindow.document.close();
            printWindow.print();
        }
    </script>
@endpush
