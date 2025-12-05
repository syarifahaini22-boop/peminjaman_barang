@extends('layouts.app')

@section('title', 'Dashboard')
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h3 mb-1">Dashboard</h2>
                <p class="text-muted mb-0">Sistem Peminjaman Barang Lab RSI</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-primary btn-custom">
                    <i class="bi bi-plus-circle me-1"></i> Pinjam Barang
                </button>
                <button class="btn btn-success btn-custom">
                    <i class="bi bi-printer me-1"></i> Cetak Laporan
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card card-custom stat-card primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted">Total Barang</h6>
                                <h3 class="mb-0">45</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-box-seam text-primary fs-1"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">
                                <i class="bi bi-arrow-up text-success"></i> 5 barang baru
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card card-custom stat-card success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted">Barang Tersedia</h6>
                                <h3 class="mb-0">32</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check-circle text-success fs-1"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-muted">Ready for use</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card card-custom stat-card warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted">Sedang Dipinjam</h6>
                                <h3 class="mb-0">8</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-clock-history text-warning fs-1"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-warning">2 akan segera kembali</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card card-custom stat-card danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="text-muted">Terlambat</h6>
                                <h3 class="mb-0">2</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-exclamation-triangle text-danger fs-1"></i>
                            </div>
                        </div>
                        <div class="mt-2">
                            <small class="text-danger">Perlu tindakan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Tables -->
        <div class="row">
            <!-- Peminjaman Aktif -->
            <div class="col-md-8 mb-4">
                <div class="card card-custom">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clock-history me-2"></i> Peminjaman Aktif
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Nama Mahasiswa</th>
                                        <th>Barang</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Batas Kembali</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>PINJ-001</td>
                                        <td>
                                            <div class="fw-medium">Ahmad Fauzi</div>
                                            <small class="text-muted">20220810001</small>
                                        </td>
                                        <td>Laptop Asus X441U</td>
                                        <td>15 Mar 2024</td>
                                        <td>22 Mar 2024</td>
                                        <td>
                                            <span class="badge bg-warning badge-status">Dipinjam</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-success">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>PINJ-002</td>
                                        <td>
                                            <div class="fw-medium">Siti Nurhaliza</div>
                                            <small class="text-muted">20220810002</small>
                                        </td>
                                        <td>Multimeter Digital</td>
                                        <td>18 Mar 2024</td>
                                        <td>25 Mar 2024</td>
                                        <td>
                                            <span class="badge bg-warning badge-status">Dipinjam</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-success">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="table-warning">
                                        <td>PINJ-003</td>
                                        <td>
                                            <div class="fw-medium">Budi Santoso</div>
                                            <small class="text-muted">20220810003</small>
                                        </td>
                                        <td>Oscilloscope</td>
                                        <td>10 Mar 2024</td>
                                        <td>17 Mar 2024</td>
                                        <td>
                                            <span class="badge bg-danger badge-status">Terlambat</span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-danger">
                                                <i class="bi bi-exclamation-triangle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('peminjaman.index') }}" class="btn btn-outline-primary btn-sm">
                                Lihat Semua Peminjaman <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-md-4 mb-4">
                <div class="card card-custom">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-lightning-charge me-2"></i> Aksi Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('barang.create') }}" class="btn btn-primary btn-custom">
                                <i class="bi bi-plus-circle me-2"></i> Tambah Barang Baru
                            </a>
                            <a href="{{ route('peminjaman.create') }}" class="btn btn-success btn-custom">
                                <i class="bi bi-calendar-plus me-2"></i> Peminjaman Baru
                            </a>
                            <button class="btn btn-info btn-custom">
                                <i class="bi bi-qr-code me-2"></i> Scan QR Code
                            </button>
                            <button class="btn btn-warning btn-custom">
                                <i class="bi bi-file-earmark-pdf me-2"></i> Export Laporan
                            </button>
                        </div>

                        <div class="mt-4">
                            <h6 class="mb-3">Statistik Cepat</h6>
                            <div class="list-group">
                                <div class="list-group-item border-0 bg-light">
                                    <div class="d-flex justify-content-between">
                                        <span>Barang Elektronik:</span>
                                        <strong>15 items</strong>
                                    </div>
                                </div>
                                <div class="list-group-item border-0">
                                    <div class="d-flex justify-content-between">
                                        <span>Alat Lab:</span>
                                        <strong>22 items</strong>
                                    </div>
                                </div>
                                <div class="list-group-item border-0 bg-light">
                                    <div class="d-flex justify-content-between">
                                        <span>Buku Referensi:</span>
                                        <strong>8 items</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Barang Terbaru -->
        <div class="row">
            <div class="col-12">
                <div class="card card-custom">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-box-seam me-2"></i> Barang Terbaru Ditambahkan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <i class="bi bi-laptop text-primary fs-1 mb-2"></i>
                                        <h6>Laptop HP</h6>
                                        <small class="text-muted">ELK-001</small>
                                        <div class="mt-2">
                                            <span class="badge bg-success">Tersedia</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <i class="bi bi-tools text-warning fs-1 mb-2"></i>
                                        <h6>Multimeter</h6>
                                        <small class="text-muted">LAB-015</small>
                                        <div class="mt-2">
                                            <span class="badge bg-warning">Dipinjam</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <i class="bi bi-book text-info fs-1 mb-2"></i>
                                        <h6>Jaringan Komputer</h6>
                                        <small class="text-muted">BUK-003</small>
                                        <div class="mt-2">
                                            <span class="badge bg-success">Tersedia</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <i class="bi bi-cpu text-danger fs-1 mb-2"></i>
                                        <h6>Arduino Uno</h6>
                                        <small class="text-muted">ELK-023</small>
                                        <div class="mt-2">
                                            <span class="badge bg-success">Tersedia</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
