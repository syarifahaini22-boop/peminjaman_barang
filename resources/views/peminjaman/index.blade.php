@extends('layouts.app')

@section('title', 'Data Peminjaman')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4">Data Peminjaman Barang</h2>
        <a href="{{ route('peminjaman.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Peminjaman Baru
        </a>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('peminjaman.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" id="filterStatus">
                            <option value="">Semua Status</option>
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Sedang Dipinjam</option>
                            <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                            <option value="terlambat" {{ request('status') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" id="filterTanggalMulai" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="end_date" class="form-control" id="filterTanggalSelesai" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cari</label>
                        <div class="input-group">
                            <input type="text" name="keyword" class="form-control" placeholder="Nama/Barang/Kode">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Peminjaman -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Mahasiswa</th>
                            <th>Barang</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $pinjam)
                        <tr class="@if($pinjam->status == 'terlambat') table-warning @endif">
                            <td><strong>{{ $pinjam->kode_peminjaman }}</strong></td>
                            <td>
                                <div>{{ $pinjam->mahasiswa->name }}</div>
                                <small class="text-muted">{{ $pinjam->mahasiswa->nim ?? '-' }}</small>
                            </td>
                            <td>{{ $pinjam->barang->nama_barang ?? $pinjam->barang->nama }}</td>
                            <td>{{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d/m/Y') }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($pinjam->tanggal_kembali)->format('d/m/Y') }}
                                @if($pinjam->status == 'terlambat')
                                <br><small class="text-danger">
                                    @php
                                        $hari_terlambat = \Carbon\Carbon::parse($pinjam->tanggal_kembali)->diffInDays(now(), false);
                                    @endphp
                                    Terlambat {{ $hari_terlambat > 0 ? $hari_terlambat : 0 }} hari
                                </small>
                                @endif
                            </td>
                            <td>{{ $pinjam->jumlah }}</td>
                            <td>
                                @if($pinjam->status == 'dipinjam')
                                    <span class="badge bg-warning">Dipinjam</span>
                                @elseif($pinjam->status == 'dikembalikan')
                                    <span class="badge bg-success">Dikembalikan</span>
                                @elseif($pinjam->status == 'terlambat')
                                    <span class="badge bg-danger">Terlambat</span>
                                @endif
                            </td>
                            <td>
                                @if($pinjam->status == 'dipinjam' || $pinjam->status == 'terlambat')
                                    <form action="{{ route('peminjaman.kembalikan', $pinjam->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Kembalikan" onclick="return confirm('Apakah barang sudah dikembalikan?')">
                                            <i class="bi bi-box-arrow-in-left"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                <a href="{{ route('peminjaman.show', $pinjam->id) }}" class="btn btn-sm btn-info" title="Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                
                                @if($pinjam->status == 'dipinjam')
                                    <a href="{{ route('peminjaman.edit', $pinjam->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data peminjaman</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $riwayat->links() }}
            </div>
        </div>
    </div>
</div>
@endsection