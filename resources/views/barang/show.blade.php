@extends('layouts.app')

@section('title', 'Detail Barang')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Kolom Kiri: Informasi Barang -->
            <div class="col-md-8">
                <div class="card card-custom mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-box-seam me-2"></i> Detail Barang
                        </h4>
                        <div class="btn-group">
                            <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil me-1"></i> Edit
                            </a>
                            <a href="{{ route('barang.index') }}" class="btn btn-secondary btn-sm">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Informasi Utama -->
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Kode Barang</th>
                                        <td>
                                            <span class="badge bg-primary fs-6">{{ $barang->kode_barang }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <td class="fw-bold">{{ $barang->nama_barang }}</td>
                                    </tr>
                                    <tr>
                                        <th>Kategori</th>
                                        <td>
                                            @php
                                                $kategoriColors = [
                                                    'elektronik' => 'primary',
                                                    'alat_lab' => 'success',
                                                    'buku' => 'info',
                                                    'perlengkapan' => 'warning',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $kategoriColors[$barang->kategori] ?? 'secondary' }}">
                                                {{ ucfirst(str_replace('_', ' ', $barang->kategori)) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Merek</th>
                                        <td>{{ $barang->merek ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td>
                                            <span class="badge bg-{{ $barang->status_badge }} fs-6">
                                                {{ ucfirst($barang->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Informasi Tambahan -->
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="40%">Lokasi</th>
                                        <td>
                                            <i class="bi bi-geo-alt me-1"></i> {{ $barang->lokasi }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Kondisi</th>
                                        <td>
                                            <span class="badge bg-{{ $barang->kondisi_badge }}">
                                                {{ ucfirst(str_replace('_', ' ', $barang->kondisi)) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tahun Pengadaan</th>
                                        <td>{{ $barang->tahun_pengadaan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Input</th>
                                        <td>{{ $barang->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <th>Terakhir Update</th>
                                        <td>{{ $barang->updated_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mt-4">
                            <h6>Deskripsi Barang</h6>
                            <div class="card bg-light">
                                <div class="card-body">
                                    @if ($barang->deskripsi)
                                        {{ $barang->deskripsi }}
                                    @else
                                        <span class="text-muted fst-italic">Tidak ada deskripsi</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Gambar & Aksi Cepat -->
            <div class="col-md-4">
                <!-- Gambar Barang -->
                <div class="card card-custom mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-image me-2"></i> Gambar Barang
                        </h6>
                    </div>
                    <div class="card-body text-center">
                        @if ($barang->gambar)
                            <img src="{{ asset('storage/' . $barang->gambar) }}" alt="{{ $barang->nama_barang }}"
                                class="img-fluid rounded" style="max-height: 250px;">
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $barang->gambar) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-zoom-in me-1"></i> Lihat Full
                                </a>
                            </div>
                        @else
                            <div class="text-muted py-5">
                                <i class="bi bi-image display-6 d-block mb-3"></i>
                                <p>Tidak ada gambar</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Di bagian Aksi Cepat -->
                <div class="card card-custom">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-lightning-charge me-2"></i> Aksi Cepat
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if ($barang->status == 'tersedia')
                            @endif

                            <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-custom btn-custom-warning">
                                <i class="bi bi-pencil me-2"></i> Edit Barang
                            </a>

                            @if ($barang->status != 'dipinjam')
                                <form action="{{ route('barang.destroy', $barang->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus barang {{ $barang->nama_barang }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-custom btn-custom-danger w-100">
                                        <i class="bi bi-trash me-2"></i> Hapus Barang
                                    </button>
                                </form>
                            @endif

                        </div>
                    </div>
                </div>

                <!-- Informasi Status -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card card-custom">
                            <div class="card-header">
                                <h6 class="card-title mb-0">
                                    <i class="bi bi-info-circle me-2"></i> Informasi Status
                                </h6>
                            </div>
                            <div class="card-body">
                                @if ($barang->status == 'dipinjam')
                                    <div class="alert alert-warning">
                                        <h6><i class="bi bi-exclamation-triangle me-2"></i> Barang Sedang Dipinjam</h6>
                                        <p class="mb-0">Barang ini sedang dalam status dipinjam dan tidak tersedia untuk
                                            peminjaman lain.</p>
                                    </div>
                                @elseif($barang->status == 'rusak')
                                    <div class="alert alert-danger">
                                        <h6><i class="bi bi-tools me-2"></i> Barang Rusak</h6>
                                        <p class="mb-0">Barang ini dalam kondisi rusak dan tidak dapat dipinjam. Perlu
                                            perbaikan.</p>
                                    </div>
                                @elseif($barang->status == 'maintenance')
                                    <div class="alert alert-info">
                                        <h6><i class="bi bi-wrench me-2"></i> Barang Dalam Perawatan</h6>
                                        <p class="mb-0">Barang ini sedang dalam proses maintenance/perawatan.</p>
                                    </div>
                                @else
                                    <div class="alert alert-success">
                                        <h6><i class="bi bi-check-circle me-2"></i> Barang Tersedia</h6>
                                        <p class="mb-0">Barang ini tersedia dan siap untuk dipinjam.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endsection
