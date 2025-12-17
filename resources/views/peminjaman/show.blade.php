@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i> Detail Peminjaman
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-user-graduate me-2"></i> Informasi Peminjaman
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">Kode Peminjaman</th>
                                                <td>
                                                    <span
                                                        class="badge bg-primary fs-6">{{ $peminjaman->kode_peminjaman }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    @if ($peminjaman->status == 'dipinjam')
                                                        <span class="badge bg-warning fs-6">Dipinjam</span>
                                                    @elseif($peminjaman->status == 'dikembalikan')
                                                        <span class="badge bg-success fs-6">Dikembalikan</span>
                                                    @else
                                                        <span class="badge bg-danger fs-6">Terlambat</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Pinjam</th>
                                                <td>
                                                    <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                                    {{ $peminjaman->tanggal_peminjaman->format('d F Y') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Tanggal Kembali</th>
                                                <td>
                                                    <i class="fas fa-calendar-check me-2 text-primary"></i>
                                                    {{ $peminjaman->tanggal_pengembalian->format('d F Y') }}
                                                </td>
                                            </tr>
                                            @if ($peminjaman->tanggal_dikembalikan)
                                                <tr>
                                                    <th>Tanggal Dikembalikan</th>
                                                    <td>
                                                        <i class="fas fa-check-circle me-2 text-success"></i>
                                                        {{ $peminjaman->tanggal_dikembalikan->format('d F Y') }}
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">
                                            <i class="fas fa-user me-2"></i> Informasi Peminjam
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless">
                                            <tr>
                                                <th width="40%">Nama Mahasiswa</th>
                                                <td>
                                                    <strong>{{ $peminjaman->mahasiswa->name ?? 'Tidak Diketahui' }}</strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>NIM</th>
                                                <td>{{ $peminjaman->mahasiswa->nim ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jurusan</th>
                                                <td>{{ $peminjaman->mahasiswa->jurusan ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Tujuan Peminjaman</th>
                                                <td>{{ $peminjaman->tujuan_peminjaman ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Lokasi Penggunaan</th>
                                                <td>{{ $peminjaman->lokasi_penggunaan ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Catatan</th>
                                                <td>{{ $peminjaman->catatan ?? '-' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- DAFTAR BARANG YANG DIPINJAM -->
                        <div class="card mb-4">
                            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-boxes me-2"></i> Daftar Barang yang Dipinjam
                                    <span class="badge bg-primary ms-2">{{ $peminjaman->barang->count() }} Barang</span>
                                </h6>
                                @if ($peminjaman->barang->count() > 0)
                                    <span class="badge bg-info">
                                        Total: {{ $peminjaman->barang->sum('pivot.jumlah') }} item
                                    </span>
                                @endif
                            </div>
                            <div class="card-body">
                                @if ($peminjaman->barang->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="50">No</th>
                                                    <th>Kode Barang</th>
                                                    <th>Nama Barang</th>
                                                    <th>Kategori</th>
                                                    <th width="100">Jumlah</th>
                                                    <th>Status Barang</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($peminjaman->barang as $index => $barang)
                                                    <tr>
                                                        <td class="text-center">{{ $index + 1 }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-secondary">{{ $barang->kode_barang }}</span>
                                                        </td>
                                                        <td>{{ $barang->nama }}</td>
                                                        <td>
                                                            @php
                                                                $kategoriLabels = [
                                                                    'elektronik' => 'Elektronik',
                                                                    'alat_lab' => 'Alat Lab',
                                                                    'buku' => 'Buku',
                                                                    'perlengkapan' => 'Perlengkapan',
                                                                ];
                                                            @endphp
                                                            <span class="badge bg-info">
                                                                {{ $kategoriLabels[$barang->kategori] ?? $barang->kategori }}
                                                            </span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span
                                                                class="badge bg-primary fs-6">{{ $barang->pivot->jumlah }}</span>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $statusColors = [
                                                                    'tersedia' => 'success',
                                                                    'dipinjam' => 'warning',
                                                                    'rusak' => 'danger',
                                                                    'maintenance' => 'info',
                                                                ];
                                                            @endphp
                                                            <span
                                                                class="badge bg-{{ $statusColors[$barang->status] ?? 'secondary' }}">
                                                                {{ ucfirst($barang->status) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr class="table-light">
                                                    <td colspan="4" class="text-end"><strong>Total Jumlah:</strong></td>
                                                    <td class="text-center">
                                                        <strong>{{ $peminjaman->barang->sum('pivot.jumlah') }}</strong>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">Tidak ada data barang</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- STATUS & PENGEMBALIAN -->
                        @if ($peminjaman->status == 'dipinjam')
                            <div class="alert alert-warning">
                                <div class="d-flex">
                                    <i class="fas fa-exclamation-triangle fa-2x me-3 mt-1"></i>
                                    <div>
                                        <h5 class="alert-heading">Barang Masih Dipinjam</h5>
                                        @if ($peminjaman->is_terlambat)
                                            <p class="mb-0">
                                                <strong class="text-danger">
                                                    <i class="fas fa-clock me-1"></i>
                                                    Sudah melewati batas waktu pengembalian!
                                                </strong>
                                                <br>
                                                Terlambat
                                                {{ now()->diffInDays($peminjaman->tanggal_pengembalian, false) * -1 }} hari
                                            </p>
                                        @else
                                            <p class="mb-0">
                                                Harap dikembalikan sebelum
                                                <strong>{{ $peminjaman->tanggal_pengembalian->format('d F Y') }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    Sisa waktu:
                                                    {{ now()->diffInDays($peminjaman->tanggal_pengembalian, false) }} hari
                                                </small>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- TOMBOL AKSI -->
                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                            <div>
                                <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                                </a>
                                @if ($peminjaman->status == 'dipinjam')
                                    <a href="{{ route('peminjaman.edit', $peminjaman->id) }}" class="btn btn-warning ms-2">
                                        <i class="fas fa-edit me-1"></i> Edit Peminjaman
                                    </a>
                                @endif
                            </div>

                            @if ($peminjaman->status == 'dipinjam')
                                <div>
                                    <form action="{{ route('peminjaman.kembalikan', $peminjaman->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-lg"
                                            onclick="return confirm('Apakah semua barang sudah dikembalikan?')">
                                            <i class="fas fa-check-circle me-1"></i> Tandai Sudah Dikembalikan
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
