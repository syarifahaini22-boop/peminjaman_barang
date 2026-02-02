@extends('layouts.app')

@section('title', 'Laporan Pengembalian')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">

                        <!-- Filter Form -->
                        <div class="card mb-4">
                            <div class="card-body">
                                <form method="GET" action="{{ route('laporan.pengembalian') }}" id="filterForm">
                                    <div class="row g-3">
                                        <div class="col-md-8">
                                            <label for="search" class="form-label">Cari Nama/Barang</label>
                                            <input type="text" class="form-control" id="search" name="search"
                                                placeholder="Cari nama mahasiswa, NIM, nama barang, atau kode peminjaman..."
                                                value="{{ $search ?? '' }}">
                                        </div>
                                        <div class="col-md-4 d-flex align-items-end">
                                            <div class="d-grid gap-2 d-md-flex w-100">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-search me-1"></i> Cari
                                                </button>
                                                @if (!empty($search))
                                                    <a href="{{ route('laporan.pengembalian') }}" class="btn btn-secondary">
                                                        <i class="fas fa-times me-1"></i> Reset
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Pesan jika ada pencarian -->
                        @if (!empty($search))
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                Menampilkan hasil pencarian untuk: <strong>"{{ $search }}"</strong>
                                <span class="float-end">
                                    Ditemukan {{ $pengembalian->total() }} data
                                </span>
                            </div>
                        @endif


                        <!-- Tabel Pengembalian -->
                        <div class="card">
                            <div class="card-body">
                                @if ($pengembalian->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="50">No</th>
                                                    <th>Kode Peminjaman</th>
                                                    <th>Barang</th>
                                                    <th>Mahasiswa</th>
                                                    <th>Tanggal Pinjam</th>
                                                    <th>Tanggal Kembali</th>
                                                    <th>Tanggal Dikembalikan</th>
                                                    <th>Status</th>
                                                    <th>Keterlambatan</th>
                                                    <th>Kondisi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pengembalian as $item)
                                                    <tr>
                                                        <td>{{ ($pengembalian->currentPage() - 1) * $pengembalian->perPage() + $loop->iteration }}
                                                        </td>
                                                        <td>
                                                            <strong>{{ $item->kode_peminjaman }}</strong>
                                                        </td>
                                                        <td>
                                                            @if ($item->barang->count() > 0)
                                                                <div class="d-flex flex-column">
                                                                    @foreach ($item->barang as $barang)
                                                                        <span class="mb-1">
                                                                            {{ $barang->nama }}
                                                                            <span class="badge bg-secondary ms-1">
                                                                                {{ $barang->pivot->jumlah ?? 1 }} pcs
                                                                            </span>
                                                                        </span>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div>{{ $item->mahasiswa->name ?? 'N/A' }}</div>
                                                            <small
                                                                class="text-muted">{{ $item->mahasiswa->nim ?? '-' }}</small>
                                                        </td>
                                                        <td>{{ $item->tanggal_peminjaman->format('d/m/Y') }}</td>
                                                        <td>{{ $item->tanggal_pengembalian->format('d/m/Y') }}</td>
                                                        <td>
                                                            @if ($item->tanggal_dikembalikan)
                                                                {{ $item->tanggal_dikembalikan->format('d/m/Y') }}
                                                            @else
                                                                <span class="text-muted">Belum dikembalikan</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($item->status == 'terlambat')
                                                                <span class="badge bg-warning">Terlambat</span>
                                                            @elseif($item->status == 'dikembalikan')
                                                                <span class="badge bg-success">Tepat Waktu</span>
                                                            @else
                                                                <span
                                                                    class="badge bg-secondary">{{ ucfirst($item->status) }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($item->status == 'terlambat' && $item->tanggal_dikembalikan)
                                                                @php
                                                                    $terlambatHari = $item->tanggal_dikembalikan->diffInDays(
                                                                        $item->tanggal_pengembalian,
                                                                    );
                                                                @endphp
                                                                <span class="text-warning fw-bold">{{ $terlambatHari }}
                                                                    hari</span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($item->kondisi_kembali)
                                                                <span
                                                                    class="badge bg-{{ $item->kondisi_kembali == 'baik' ? 'success' : 'danger' }}">
                                                                    {{ ucfirst($item->kondisi_kembali) }}
                                                                </span>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Pagination -->
                                    @if ($pengembalian->hasPages())
                                        <div class="mt-4">
                                            {{ $pengembalian->appends(['search' => $search])->links() }}
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <h5>Tidak ada data pengembalian</h5>
                                            <p class="mb-4">
                                                @if (!empty($search))
                                                    Data pengembalian tidak ditemukan untuk pencarian "{{ $search }}"
                                                @else
                                                    Belum ada data pengembalian barang.
                                                @endif
                                            </p>
                                            @if (!empty($search))
                                                <a href="{{ route('laporan.pengembalian') }}" class="btn btn-primary">
                                                    <i class="fas fa-list"></i> Lihat Semua Data
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            border-radius: 10px;
            overflow: hidden;
        }

        .table th {
            font-weight: 600;
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 500;
        }

        .alert {
            border-radius: 10px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Auto-focus ke search field
            $('#search').focus();

            // Submit form ketika menekan Enter di input search
            $('#search').on('keypress', function(e) {
                if (e.which === 13) { // 13 adalah keycode untuk Enter
                    e.preventDefault();
                    $('#filterForm').submit();
                }
            });

            // Clear input ketika diklik tombol Reset
            $('.btn-secondary').on('click', function() {
                $('#search').val('');
            });
        });
    </script>
@endpush
