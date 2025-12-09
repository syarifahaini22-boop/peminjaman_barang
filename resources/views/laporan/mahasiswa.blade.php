@extends('layouts.app')

@section('title', 'Laporan Mahasiswa')

@section('content')
    <div class="container-fluid px-4 py-3">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">


        </div>



        <div class="row">
            <!-- Daftar Mahasiswa -->
            <div class="col-xl-8 mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">
                                <i class="fas fa-list me-2 text-primary"></i>Daftar Mahasiswa
                            </h5>
                            <div class="input-group" style="width: 250px;">
                                <input type="text" class="form-control" placeholder="Cari mahasiswa..."
                                    id="searchMahasiswa">
                                <button class="btn btn-outline-secondary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="mahasiswaTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">#</th>
                                        <th>Mahasiswa</th>
                                        <th>Jurusan</th>
                                        <th>Total Peminjaman</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mahasiswa as $index => $mhs)
                                        <tr>
                                            <td class="ps-4">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div
                                                        class="avatar-circle-sm bg-primary bg-opacity-10 text-primary me-3">
                                                        <span>{{ substr($mhs->name, 0, 2) }}</span>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $mhs->name }}</div>
                                                        <small class="text-muted">{{ $mhs->nim }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info bg-opacity-10 text-info">
                                                    {{ $mhs->jurusan }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                        <div class="progress-bar bg-primary"
                                                            style="width: {{ min($mhs->total_peminjaman * 10, 100) }}%">
                                                        </div>
                                                    </div>
                                                    <span class="fw-bold">{{ $mhs->total_peminjaman }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $status =
                                                        $mhs->total_peminjaman > 10
                                                            ? 'Aktif'
                                                            : ($mhs->total_peminjaman > 5
                                                                ? 'Sedang'
                                                                : 'Pasif');
                                                    $color =
                                                        $mhs->total_peminjaman > 10
                                                            ? 'success'
                                                            : ($mhs->total_peminjaman > 5
                                                                ? 'warning'
                                                                : 'secondary');
                                                @endphp
                                                <span
                                                    class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }}">
                                                    {{ $status }}
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <button class="btn btn-sm btn-outline-primary btn-detail"
                                                    data-bs-toggle="modal" data-bs-target="#detailModal"
                                                    data-id="{{ $mhs->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                Menampilkan {{ $mahasiswa->count() }} dari {{ $mahasiswa->count() }} mahasiswa
                            </div>
                            <nav>
                                <ul class="pagination mb-0">
                                    <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                                    <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik -->
            <div class="col-xl-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-chart-pie me-2 text-success"></i>Statistik Mahasiswa
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Chart Jurusan -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Distribusi Jurusan</h6>
                            <canvas id="jurusanChart" height="200"></canvas>
                        </div>

                        <!-- Top 5 Mahasiswa -->
                        <div>
                            <h6 class="fw-bold mb-3">Top 5 Mahasiswa Teraktif</h6>
                            <div class="list-group list-group-flush">
                                @foreach ($topMahasiswa->take(5) as $index => $mhs)
                                    <div class="list-group-item border-0 px-0 py-2">
                                        <div class="d-flex align-items-center">
                                            <div class="rank-circle bg-primary text-white fw-bold me-3">
                                                {{ $index + 1 }}</div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $mhs->name }}</h6>
                                                <small class="text-muted">{{ $mhs->jurusan }}</small>
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-bold text-primary">{{ $mhs->peminjaman_count }}x</div>
                                                <small class="text-muted">peminjaman</small>
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

        <!-- Peminjaman Terbaru -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="fw-bold mb-0">
                            <i class="fas fa-history me-2 text-warning"></i>Peminjaman Terbaru
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Mahasiswa</th>
                                        <th>Barang</th>
                                        <th>Status</th>
                                        <th>Tanggal Kembali</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $recentPeminjaman = App\Models\Peminjaman::with(['user', 'barang'])
                                            ->latest()
                                            ->take(8)
                                            ->get();
                                    @endphp
                                    @foreach ($recentPeminjaman as $pinjam)
                                        <tr>
                                            <td>{{ $pinjam->tanggal_peminjaman->format('d/m/Y') }}</td>
                                            <td>
                                                <div class="fw-medium">{{ $pinjam->user->name }}</div>
                                                <small class="text-muted">{{ $pinjam->user->nim }}</small>
                                            </td>
                                            <td>{{ $pinjam->barang->nama }}</td>

                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'dipinjam' => 'warning',
                                                        'dikembalikan' => 'success',
                                                        'terlambat' => 'danger',
                                                        'hilang' => 'dark',
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$pinjam->status] ?? 'secondary' }}">
                                                    {{ ucfirst($pinjam->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($pinjam->tanggal_dikembalikan)
                                                    {{ $pinjam->tanggal_dikembalikan->format('d/m/Y') }}
                                                @else
                                                    <span class="text-muted">-</span>
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
        </div>
    </div>

    <!-- Modal Detail Mahasiswa -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="detailModalLabel">
                        <i class="fas fa-user-graduate me-2"></i>Detail Mahasiswa
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detailContent">
                    <!-- Content akan diisi via JavaScript -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>

                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="filterModalLabel">
                        <i class="fas fa-filter me-2"></i>Filter Laporan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm">
                        <div class="mb-3">
                            <label class="form-label">Periode</label>
                            <input type="text" class="form-control date-range-picker" name="periode">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jurusan</label>
                            <select class="form-select" name="jurusan">
                                <option value="">Semua Jurusan</option>
                                @foreach ($jurusanStats as $jurusan)
                                    <option value="{{ $jurusan->jurusan }}">{{ $jurusan->jurusan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status Aktivitas</label>
                            <select class="form-select" name="status">
                                <option value="">Semua Status</option>
                                <option value="aktif">Aktif (>10 peminjaman)</option>
                                <option value="sedang">Sedang (5-10 peminjaman)</option>
                                <option value="pasif">Pasif (<5 peminjaman)</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="applyFilter()">Terapkan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .avatar-circle-sm {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }

        .rank-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .icon-wrapper {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .progress {
            border-radius: 10px;
        }

        .btn-detail {
            padding: 5px 10px;
            border-radius: 6px;
        }

        @media (max-width: 768px) {
            .card-body {
                padding: 1rem !important;
            }

            .input-group {
                width: 100% !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css">

    <script>
        $(document).ready(function() {
            // Initialize date range picker
            const picker = new Litepicker({
                element: document.querySelector('.date-range-picker'),
                singleMode: false,
                numberOfMonths: 2,
                numberOfColumns: 2,
                format: 'DD/MM/YYYY'
            });

            // Chart Jurusan
            const jurusanCtx = document.getElementById('jurusanChart').getContext('2d');
            new Chart(jurusanCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($jurusanStats->pluck('jurusan')) !!},
                    datasets: [{
                        data: {!! json_encode($jurusanStats->pluck('total')) !!},
                        backgroundColor: [
                            '#4e73df', '#1cc88a', '#f6c23e', '#e74a3b', '#36b9cc',
                            '#6c757d', '#858796', '#dddfeb', '#5a5c69'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });

            // Search functionality
            $('#searchMahasiswa').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('#mahasiswaTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Detail modal
            $('.btn-detail').click(function() {
                const mhsId = $(this).data('id');
                loadMahasiswaDetail(mhsId);
            });
        });

        function loadMahasiswaDetail(id) {
            // Simulasi data - dalam implementasi real, gunakan AJAX
            const detailContent = `
        <div class="row">
            <div class="col-md-4 text-center">
                <div class="avatar-circle-lg bg-primary bg-opacity-10 text-primary mx-auto mb-3">
                    <span>JD</span>
                </div>
                <h5 class="fw-bold">John Doe</h5>
                <p class="text-muted mb-2">20220810001</p>
                <span class="badge bg-info">Teknik Informatika</span>
            </div>
            <div class="col-md-8">
                <div class="row mb-3">
                    <div class="col-6">
                        <small class="text-muted">Email</small>
                        <p class="fw-medium">john.doe@student.example.com</p>
                    </div>
                    <div class="col-6">
                        <small class="text-muted">No. Telepon</small>
                        <p class="fw-medium">081234567890</p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-4">
                        <div class="text-center">
                            <h3 class="fw-bold text-primary">25</h3>
                            <small class="text-muted">Total Peminjaman</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <h3 class="fw-bold text-success">22</h3>
                            <small class="text-muted">Tepat Waktu</small>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="text-center">
                            <h3 class="fw-bold text-warning">3</h3>
                            <small class="text-muted">Sedang Dipinjam</small>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <h6 class="fw-bold mb-2">Riwayat Peminjaman Terakhir</h6>
                    <div class="list-group">
                        <div class="list-group-item border-0 bg-light">
                            <div class="d-flex justify-content-between">
                                <span>Laptop Asus - 15 Mar 2024</span>
                                <span class="badge bg-warning">Dipinjam</span>
                            </div>
                        </div>
                        <div class="list-group-item border-0">
                            <div class="d-flex justify-content-between">
                                <span>Multimeter - 10 Mar 2024</span>
                                <span class="badge bg-success">Dikembalikan</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

            $('#detailContent').html(detailContent);
        }

        function applyFilter() {
            const formData = $('#filterForm').serialize();
            // Implementasi filter AJAX
            console.log('Filter diterapkan:', formData);
            $('#filterModal').modal('hide');
            alert('Filter diterapkan! (Implementasi AJAX)');
        }

        function exportToExcel() {
            // Implementasi export Excel
            alert('Fitur export Excel akan segera tersedia!');
        }

        function printDetail() {
            const printContent = $('#detailContent').html();
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
        <html>
            <head>
                <title>Detail Mahasiswa - Sistem Peminjaman Lab RSI</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { padding: 20px; }
                    .avatar-circle-lg {
                        width: 100px;
                        height: 100px;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 2rem;
                        font-weight: bold;
                    }
                </style>
            </head>
            <body>
                <h3>Detail Mahasiswa - {{ date('d F Y') }}</h3>
                ${printContent}
            </body>
        </html>
    `);
            printWindow.document.close();
            printWindow.print();
        }

        // Custom CSS
        const style = document.createElement('style');
        style.textContent = `
    .avatar-circle-lg {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: bold;
    }
`;
        document.head.appendChild(style);
    </script>
@endpush
