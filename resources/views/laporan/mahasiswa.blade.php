@extends('layouts.app')

@section('title', 'Laporan Mahasiswa')

@section('content')
    <div class="container-fluid px-4 py-3">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">


        </div>

        <div class="row">
            <!-- Daftar Mahasiswa -->
            <div class="col-12 mb-4">
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
                                        <th>NIM</th>
                                        <th>Jurusan</th>
                                        <th class="text-center">Total Peminjaman</th>
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
                                                        <small class="text-muted">{{ $mhs->email ?? '-' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $mhs->nim }}</td>
                                            <td>
                                                <div class="fw-medium">{{ $mhs->jurusan }}</div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary bg-opacity-10 text-primary fw-bold"
                                                    style="font-size: 14px; padding: 6px 12px;">
                                                    {{ $mhs->total_peminjaman ?? 0 }}
                                                </span>
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
                                @if ($mahasiswa instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                    Menampilkan {{ $mahasiswa->firstItem() ?? 0 }} - {{ $mahasiswa->lastItem() ?? 0 }} dari
                                    {{ $mahasiswa->total() }} mahasiswa
                                @else
                                    Menampilkan {{ $mahasiswa->count() }} mahasiswa
                                @endif
                            </div>
                            @if ($mahasiswa instanceof \Illuminate\Pagination\LengthAwarePaginator && $mahasiswa->hasPages())
                                <nav>
                                    <ul class="pagination mb-0">
                                        @if ($mahasiswa->onFirstPage())
                                            <li class="page-item disabled"><a class="page-link" href="#">Previous</a>
                                            </li>
                                        @else
                                            <li class="page-item"><a class="page-link"
                                                    href="{{ $mahasiswa->previousPageUrl() }}">Previous</a></li>
                                        @endif

                                        @for ($i = 1; $i <= $mahasiswa->lastPage(); $i++)
                                            <li class="page-item {{ $mahasiswa->currentPage() == $i ? 'active' : '' }}">
                                                <a class="page-link"
                                                    href="{{ $mahasiswa->url($i) }}">{{ $i }}</a>
                                            </li>
                                        @endfor

                                        @if ($mahasiswa->hasMorePages())
                                            <li class="page-item"><a class="page-link"
                                                    href="{{ $mahasiswa->nextPageUrl() }}">Next</a></li>
                                        @else
                                            <li class="page-item disabled"><a class="page-link" href="#">Next</a></li>
                                        @endif
                                    </ul>
                                </nav>
                            @endif
                        </div>
                    </div>
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
                            <label class="form-label">Jurusan</label>
                            <select class="form-select" name="jurusan">
                                <option value="">Semua Jurusan</option>
                                @php
                                    $jurusanList = $mahasiswa->pluck('jurusan')->unique()->filter();
                                @endphp
                                @foreach ($jurusanList as $jurusan)
                                    <option value="{{ $jurusan }}">{{ $jurusan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total Peminjaman</label>
                            <select class="form-select" name="total_peminjaman">
                                <option value="">Semua</option>
                                <option value="0-5">0-5 kali</option>
                                <option value="6-10">6-10 kali</option>
                                <option value="11-20">11-20 kali</option>
                                <option value="20+">Lebih dari 20 kali</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Urutkan Berdasarkan</label>
                            <select class="form-select" name="sort">
                                <option value="total_desc">Total Peminjaman (Tertinggi)</option>
                                <option value="total_asc">Total Peminjaman (Terendah)</option>
                                <option value="name_asc">Nama (A-Z)</option>
                                <option value="name_desc">Nama (Z-A)</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="applyFilter()">Terapkan Filter</button>
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

        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 500;
        }

        .table td,
        .table th {
            vertical-align: middle;
        }

        .table thead th {
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }

        .table tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .card {
            border-radius: 10px;
            overflow: hidden;
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, .125);
        }

        .list-group-item {
            border-left: 0;
            border-right: 0;
        }

        .list-group-item:first-child {
            border-top: 0;
        }

        .list-group-item:last-child {
            border-bottom: 0;
        }

        @media (max-width: 768px) {
            .input-group {
                width: 100% !important;
                margin-top: 10px;
            }

            .d-flex.justify-content-between.align-items-center {
                flex-direction: column;
                align-items: flex-start !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#searchMahasiswa').on('keyup', function() {
                const value = $(this).val().toLowerCase();
                $('#mahasiswaTable tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Chart Jurusan
            const jurusanCtx = document.getElementById('jurusanChart');
            if (jurusanCtx) {
                @php
                    $jurusanData = $mahasiswa->groupBy('jurusan')->map(function ($item) {
                        return $item->count();
                    });
                @endphp

                new Chart(jurusanCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($jurusanData->keys()->toArray()) !!},
                        datasets: [{
                            label: 'Jumlah Mahasiswa',
                            data: {!! json_encode($jurusanData->values()->toArray()) !!},
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return `Jumlah: ${context.raw} mahasiswa`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
        });

        function applyFilter() {
            // Implement filter logic here
            const form = $('#filterForm');
            const formData = form.serialize();

            // You can implement AJAX filtering or form submission here
            console.log('Filter data:', formData);
            $('#filterModal').modal('hide');

            // Show loading or process filter
            alert('Filter diterapkan! (Implementasi filter dapat ditambahkan)');
        }
    </script>
@endpush
