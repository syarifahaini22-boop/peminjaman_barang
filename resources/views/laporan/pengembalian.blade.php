@extends('layouts.app')

@section('title', 'Laporan Pengembalian')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-undo-alt"></i> Laporan Pengembalian Barang
                        </h5>
                    </div>
                    <div class="card-body">

                        <!-- Filter Form -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <form method="GET" action="{{ route('laporan.pengembalian') }}">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="start_date" class="form-label">Tanggal Mulai</label>
                                            <input type="date" class="form-control" id="start_date" name="start_date"
                                                value="{{ $start_date }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                                            <input type="date" class="form-control" id="end_date" name="end_date"
                                                value="{{ $end_date }}">
                                        </div>
                                        <div class="col-md-4 d-flex align-items-end">
                                            <div class="d-grid gap-2 d-md-flex w-100">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-filter"></i> Filter
                                                </button>
                                                <a href="{{ route('laporan.pengembalian') }}" class="btn btn-secondary">
                                                    <i class="fas fa-redo"></i> Reset
                                                </a>
                                                @if ($pengembalian->count() > 0)
                                                    <a href="{{ route('laporan.export.pdf', ['type' => 'pengembalian', 'start_date' => $start_date, 'end_date' => $end_date]) }}"
                                                        class="btn btn-danger" target="_blank">
                                                        <i class="fas fa-file-pdf"></i> PDF
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Statistik -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Total Pengembalian</h6>
                                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Tepat Waktu</h6>
                                        <h3 class="mb-0">{{ $stats['tepat_waktu'] }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Terlambat</h6>
                                        <h3 class="mb-0">{{ $stats['terlambat'] }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">% Terlambat</h6>
                                        <h3 class="mb-0">{{ $stats['persentase_terlambat'] }}%</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Pengembalian -->
                        <div class="card">
                            <div class="card-body">
                                @if ($pengembalian->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode Peminjaman</th>
                                                    <th>Barang</th>
                                                    <th>Mahasiswa</th>
                                                    <th>Tanggal Pinjam</th>
                                                    <th>Tanggal Kembali</th>
                                                    <th>Tanggal Dikembalikan</th>
                                                    <th>Status</th>
                                                    <th>Keterlambatan</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pengembalian as $item)
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td><strong>{{ $item->kode_peminjaman }}</strong></td>
                                                        <td>{{ $item->barang->nama ?? '-' }}</td>
                                                        <td>{{ $item->mahasiswa->name ?? '-' }}</td>
                                                        <td>{{ $item->tanggal_peminjaman->format('d/m/Y') }}</td>
                                                        <td>{{ $item->tanggal_pengembalian->format('d/m/Y') }}</td>
                                                        <td>{{ $item->tanggal_dikembalikan->format('d/m/Y') }}</td>
                                                        <td>
                                                            @if ($item->status == 'terlambat')
                                                                <span class="badge bg-warning">Terlambat</span>
                                                            @else
                                                                <span class="badge bg-success">Tepat Waktu</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($item->status == 'terlambat')
                                                                @php
                                                                    $terlambatHari = $item->tanggal_dikembalikan->diffInDays(
                                                                        $item->tanggal_pengembalian,
                                                                    );
                                                                @endphp
                                                                {{ $terlambatHari }} hari
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <h5>Tidak ada data pengembalian</h5>
                                            <p>Data pengembalian belum tersedia untuk periode ini.</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Periode Laporan -->
                        <div class="mt-3 text-end">
                            <small class="text-muted">
                                Periode: {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} -
                                {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
