@extends('layouts.app')

@section('title', 'Data Peminjaman')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800"></h1>
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
                            <label class="form-label">Cari</label>
                            <div class="input-group">
                                <input type="text" name="keyword" class="form-control" placeholder="Kode/Nama/Barang"
                                    value="{{ request('keyword') }}">
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
                                <th>Jumlah Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $pinjam)
                                <tr class="@if ($pinjam->status == 'terlambat') table-warning @endif">
                                    <td>
                                        <strong>{{ $pinjam->kode_peminjaman }}</strong><br>
                                        <small class="text-muted">{{ $pinjam->tujuan_peminjaman }}</small>
                                    </td>
                                    <td>
                                        <div>{{ $pinjam->mahasiswa->name ?? '-' }}</div>
                                        <small
                                            class="text-muted">{{ $pinjam->mahasiswa->nim ?? 'NIM tidak tersedia' }}</small>
                                    </td>
                                    <td>
                                        @forelse($pinjam->barang as $barang)
                                            <div>
                                                {{ $barang->nama }}
                                                <span class="badge bg-info">{{ $barang->pivot->jumlah }} pcs</span>
                                            </div>
                                        @empty
                                            <span class="text-danger">Tidak ada barang</span>
                                        @endforelse
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($pinjam->tanggal_peminjaman)->format('d/m/Y') }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($pinjam->tanggal_pengembalian)->format('d/m/Y') }}
                                        @if ($pinjam->is_terlambat && $pinjam->status == 'dipinjam')
                                            <br>
                                            <small class="text-danger">
                                                @php
                                                    $hari_terlambat = \Carbon\Carbon::parse(
                                                        $pinjam->tanggal_pengembalian,
                                                    )->diffInDays(now(), false);
                                                @endphp
                                                Telat {{ max(0, $hari_terlambat) }} hari
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $pinjam->barang->sum('pivot.jumlah') }}
                                    </td>
                                    <td>
                                        @if ($pinjam->status == 'dipinjam')
                                            <span class="badge bg-warning">Dipinjam</span>
                                            @if ($pinjam->is_terlambat)
                                                <br><small class="text-danger">(Telat)</small>
                                            @endif
                                        @elseif($pinjam->status == 'dikembalikan')
                                            <span class="badge bg-success">Dikembalikan</span>
                                            <br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($pinjam->tanggal_dikembalikan)->format('d/m/Y') }}
                                            </small>
                                        @elseif($pinjam->status == 'terlambat')
                                            <span class="badge bg-danger">Terlambat</span>
                                            <br>
                                            @if ($pinjam->tanggal_dikembalikan)
                                                <small class="text-muted">
                                                    Dikembalikan:
                                                    {{ \Carbon\Carbon::parse($pinjam->tanggal_dikembalikan)->format('d/m/Y') }}
                                                </small>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">


                                            @if ($pinjam->status == 'dipinjam' || $pinjam->status == 'terlambat')
                                                <form action="{{ route('peminjaman.kembalikan', $pinjam->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success" title="Kembalikan"
                                                        onclick="return confirm('Apakah barang sudah dikembalikan?')">
                                                        <i class="bi bi-box-arrow-in-left"></i>
                                                    </button>
                                                </form>
                                            @endif

                                            @if ($pinjam->status == 'dipinjam')
                                                <form action="{{ route('peminjaman.destroy', $pinjam->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger" title="Hapus"
                                                        onclick="return confirm('Hapus peminjaman ini?')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                            <p class="mt-2">Tidak ada data peminjaman</p>
                                        </div>
                                    </td>
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

@push('scripts')
    <script>
        $(document).ready(function() {
            // Auto submit filter saat dropdown status berubah
            $('select[name="status"]').change(function() {
                $(this).closest('form').submit();
            });
        });
    </script>
@endpush
