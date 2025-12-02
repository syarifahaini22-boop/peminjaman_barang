@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('content')
<div class="container-fluid">
    <!-- Filter Section -->
    <div class="filter-card">
        <h5><i class="fas fa-filter"></i> Filter Riwayat</h5>
        <form method="GET" action="{{ route('peminjaman.riwayat') }}">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Keyword</label>
                    <input type="text" name="keyword" class="form-control" 
                           value="{{ $keyword }}" placeholder="Cari kode/nama...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua</option>
                        <option value="dipinjam" {{ $status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="dikembalikan" {{ $status == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        <option value="terlambat" {{ $status == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Dari Tanggal</label>
                    <input type="date" name="start_date" class="form-control" value="{{ $start_date }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="form-control" value="{{ $end_date }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2 d-md-flex">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('peminjaman.riwayat') }}" class="btn btn-secondary">
                            <i class="fas fa-redo"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">
                <i class="fas fa-history"></i> Riwayat Lengkap Peminjaman
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Barang</th>
                            <th>Mahasiswa</th>
                            <th>Pinjam</th>
                            <th>Kembali</th>
                            <th>Dikembalikan</th>
                            <th>Jumlah</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayat as $item)
                            <tr>
                                <td><strong>{{ $item->kode_peminjaman }}</strong></td>
                                <td>{{ $item->barang->nama }}</td>
                                <td>{{ $item->mahasiswa->name }}</td>
                                <td>{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                                <td>{{ $item->tanggal_kembali->format('d/m/Y') }}</td>
                                <td>
                                    @if($item->tanggal_dikembalikan)
                                        {{ $item->tanggal_dikembalikan->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $item->jumlah }}</td>
                                <td>
                                    @if($item->status == 'dipinjam')
                                        <span class="badge bg-warning badge-status">Dipinjam</span>
                                    @elseif($item->status == 'dikembalikan')
                                        <span class="badge bg-success badge-status">Dikembalikan</span>
                                    @else
                                        <span class="badge bg-danger badge-status">Terlambat test</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($item->keterangan, 30) ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Tidak ada riwayat peminjaman</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $riwayat->links() }}
            </div>
        </div>
    </div>
</div>
@endsection