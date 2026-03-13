@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">

        <!-- Filter Card -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('laporan.mingguan') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" class="form-control"
                            value="{{ request('tanggal_awal', date('Y-m-d')) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small text-muted">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" class="form-control"
                            value="{{ request('tanggal_akhir', date('Y-m-d', strtotime(request('tanggal_awal', date('Y-m-d')) . ' +7 days'))) }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fas fa-filter me-2"></i>Tampilkan Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if (request()->has('tanggal_awal') && request()->has('tanggal_akhir'))
            <!-- Statistik Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-box text-warning fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Total Alat Keluar</small>
                                    <span class="h3 fw-bold mb-0">{{ number_format($totalBarangKeluar) }}</span>
                                    <small class="text-muted">unit</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-exchange-alt text-info fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Total Transaksi</small>
                                    <span class="h3 fw-bold mb-0">{{ number_format($totalTransaksi) }}</span>
                                    <small class="text-muted">kali</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper bg-success bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-cubes text-success fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Total Alat</small>
                                    <span class="h3 fw-bold mb-0">{{ number_format($totalItemUnik) }}</span>
                                    <small class="text-muted">jenis</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                {{-- <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-wrapper bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                    <i class="fas fa-tags text-primary fs-4"></i>
                                </div>
                                <div>
                                    <small class="text-muted d-block">Kategori</small>
                                    <span class="h3 fw-bold mb-0">{{ number_format($totalKategori) }}</span>
                                    <small class="text-muted">kategori</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
            </div>

            <!-- Chart dan Tabel -->
            <div class="row">
                <!-- Chart -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 py-3">
                            <h6 class="fw-bold mb-0">Grafik Barang Keluar (Harian)</h6>
                        </div>
                        <div class="card-body">
                            <canvas id="chartBarangKeluar" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Tabel Data -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0">Detail Transaksi</h6>
                            <span
                                class="badge bg-warning">{{ \Carbon\Carbon::parse(request('tanggal_awal'))->format('d/m/Y') }}
                                -
                                {{ \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y') }}</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive" style="max-height: 300px;">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-3">Tanggal</th>
                                            <th>Kode Peminjaman</th>
                                            <th>Barang</th>
                                            <th class="text-center">Jumlah</th>
                                            <th>Mahasiswa</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($peminjaman as $item)
                                            @foreach ($item->barang as $barang)
                                                <tr>
                                                    <td class="ps-3">
                                                        {{ \Carbon\Carbon::parse($item->tanggal_peminjaman)->format('d/m/Y') }}
                                                    </td>
                                                    <td>{{ $item->kode_peminjaman ?? '-' }}</td>
                                                    <td>{{ $barang->nama ?? '-' }}</td>
                                                    <td class="text-center">{{ $barang->pivot->jumlah ?? 1 }}</td>
                                                    <td>
                                                        @if ($item->mahasiswa)
                                                            {{ $item->mahasiswa->name ?? ($item->mahasiswa->nama ?? ($item->mahasiswa->nim ?? 'Mahasiswa')) }}
                                                        @else
                                                            <span class="text-muted">-</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    <i class="fas fa-inbox me-2"></i>Tidak ada data
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-0 py-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Menampilkan {{ $peminjaman->count() }} transaksi
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Pesan ketika belum memilih tanggal -->
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-calendar-alt text-warning fs-1 mb-3"></i>
                            <h5 class="fw-bold mb-2">Belum Ada Data Ditampilkan</h5>
                            <p class="text-muted mb-4">Silakan pilih rentang tanggal terlebih dahulu untuk melihat laporan
                            </p>
                            <div class="d-flex justify-content-center gap-2">
                                <span class="badge bg-warning bg-opacity-10 text-warning p-2">
                                    <i class="fas fa-calendar me-1"></i> Pilih Tanggal Awal
                                </span>
                                <span class="badge bg-warning bg-opacity-10 text-warning p-2">
                                    <i class="fas fa-calendar me-1"></i> Pilih Tanggal Akhir
                                </span>
                                <span class="badge bg-warning bg-opacity-10 text-warning p-2">
                                    <i class="fas fa-filter me-1"></i> Klik Tampilkan
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

    @push('scripts')
        @if (request()->has('tanggal_awal') && request()->has('tanggal_akhir') && !empty($chartData))
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Data dari controller
                    const chartData = @json($chartData);

                    const ctx = document.getElementById('chartBarangKeluar').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: chartData.map(item => item.tanggal),
                            datasets: [{
                                label: 'Jumlah Barang Keluar',
                                data: chartData.map(item => item.jumlah),
                                borderColor: '#ffc107',
                                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1,
                                        callback: function(value) {
                                            if (Math.floor(value) === value) {
                                                return value;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    });
                });
            </script>
        @endif
    @endpush
@endsection
