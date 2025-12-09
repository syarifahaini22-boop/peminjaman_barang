@extends('layouts.app')

@section('title', 'Laporan Peminjaman')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">


        </div>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('laporan.peminjaman') }}">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $start_date }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $end_date }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="dipinjam" {{ $status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                <option value="dikembalikan" {{ $status == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan
                                </option>
                                <option value="terlambat" {{ $status == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                            </select>
                        </div>
                        <div class="col-md-3">
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
                        <h6 class="card-title">Total Peminjaman</h6>
                        <h3 class="card-text">{{ $stats['total'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h6 class="card-title">Sedang Dipinjam</h6>
                        <h3 class="card-text">{{ $stats['dipinjam'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h6 class="card-title">Sudah Dikembalikan</h6>
                        <h3 class="card-text">{{ $stats['dikembalikan'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h6 class="card-title">Terlambat</h6>
                        <h3 class="card-text">{{ $stats['terlambat'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Laporan Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    Detail Peminjaman ({{ $start_date }} s/d {{ $end_date }})
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Barang</th>
                                <th>Mahasiswa</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tanggal Kembali</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peminjaman as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->kode_peminjaman }}</td>
                                    <td>{{ $item->barang->nama }}</td>
                                    <td>{{ $item->mahasiswa->name }}</td>
                                    <td>{{ $item->tanggal_peminjaman->format('d/m/Y') }}</td>
                                    <td>{{ $item->tanggal_pengembalian->format('d/m/Y') }}</td>
                                    <td class="text-center">{{ $item->jumlah }}</td>
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
                                    <td colspan="8" class="text-center">Tidak ada data peminjaman</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <th colspan="6" class="text-end">Total Barang Dipinjam:</th>
                                <th class="text-center">{{ $stats['total_barang'] }}</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>



    <style>
        @media print {

            .sidebar,
            .card-header .btn-group,
            .filter-card,
            .stats-cards {
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
                font-size: 12px !important;
            }
        }
    </style>
@endsection
