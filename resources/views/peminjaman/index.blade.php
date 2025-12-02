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
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select class="form-select" id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="dipinjam">Sedang Dipinjam</option>
                        <option value="dikembalikan">Sudah Dikembalikan</option>
                        <option value="terlambat">Terlambat</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="filterTanggalMulai">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" class="form-control" id="filterTanggalSelesai">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Cari</label>
                    <input type="text" class="form-control" id="searchPeminjaman" placeholder="Nama/NIM/Barang">
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Peminjaman -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="tablePeminjaman">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Mahasiswa</th>
                            <th>Barang</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($peminjaman as $pinjam)
                        <tr class="@if($pinjam->status == 'terlambat') table-warning @endif">
                            <td><strong>{{ $pinjam->kode_peminjaman }}</strong></td>
                            <td>
                                <div>{{ $pinjam->mahasiswa->nama }}</div>
                                <small class="text-muted">{{ $pinjam->mahasiswa->nim }}</small>
                            </td>
                            <td>{{ $pinjam->barang->nama_barang }}</td>
                            <td>{{ \Carbon\Carbon::parse($pinjam->tanggal_peminjaman)->format('d/m/Y') }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($pinjam->tanggal_pengembalian)->format('d/m/Y') }}
                                @if($pinjam->status == 'terlambat')
                                <br><small class="text-danger">Terlambat {{ $pinjam->hari_terlambat }} hari</small>
                                @endif
                            </td>
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
                                <button class="btn btn-sm btn-success" title="Kembalikan" onclick="kembalikanBarang({{ $pinjam->id }})">
                                    <i class="bi bi-box-arrow-in-left"></i>
                                </button>
                                @endif
                                <button class="btn btn-sm btn-info" title="Detail" data-bs-toggle="modal" data-bs-target="#detailPeminjamanModal" data-id="{{ $pinjam->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-warning" title="Edit" onclick="editPeminjaman({{ $pinjam->id }})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Peminjaman -->
<div class="modal fade" id="detailPeminjamanModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailPeminjamanContent">
                <!-- Content akan diisi via AJAX -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Filter Peminjaman
    function filterPeminjaman() {
        const status = document.getElementById('filterStatus').value;
        const tanggalMulai = document.getElementById('filterTanggalMulai').value;
        const tanggalSelesai = document.getElementById('filterTanggalSelesai').value;
        const search = document.getElementById('searchPeminjaman').value.toLowerCase();
        
        const rows = document.querySelectorAll('#tablePeminjaman tbody tr');
        
        rows.forEach(row => {
            const rowStatus = row.querySelector('td:nth-child(6)').textContent.toLowerCase();
            const rowText = row.textContent.toLowerCase();
            
            let show = true;
            
            if (status && !rowStatus.includes(status)) show = false;
            if (search && !rowText.includes(search)) show = false;
            
            row.style.display = show ? '' : 'none';
        });
    }

    document.getElementById('filterStatus').addEventListener('change', filterPeminjaman);
    document.getElementById('searchPeminjaman').addEventListener('input', filterPeminjaman);

    // Detail Peminjaman
    const detailModal = document.getElementById('detailPeminjamanModal');
    detailModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const peminjamanId = button.getAttribute('data-id');
        
        // AJAX untuk mengambil detail peminjaman
        fetch(`/peminjaman/${peminjamanId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('detailPeminjamanContent').innerHTML = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Peminjaman</h6>
                            <p><strong>Kode:</strong> ${data.kode_peminjaman}</p>
                            <p><strong>Tanggal Pinjam:</strong> ${data.tanggal_peminjaman}</p>
                            <p><strong>Tanggal Kembali:</strong> ${data.tanggal_pengembalian}</p>
                            <p><strong>Status:</strong> <span class="badge bg-${data.status === 'dikembalikan' ? 'success' : 'warning'}">${data.status}</span></p>
                        </div>
                        <div class="col-md-6">
                            <h6>Informasi Mahasiswa</h6>
                            <p><strong>Nama:</strong> ${data.mahasiswa.nama}</p>
                            <p><strong>NIM:</strong> ${data.mahasiswa.nim}</p>
                            <p><strong>Jurusan:</strong> ${data.mahasiswa.jurusan}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Informasi Barang</h6>
                            <p><strong>Nama Barang:</strong> ${data.barang.nama_barang}</p>
                            <p><strong>Kode Barang:</strong> ${data.barang.kode_barang}</p>
                            <p><strong>Kategori:</strong> ${data.barang.kategori}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Detail Lainnya</h6>
                            <p><strong>Tujuan Peminjaman:</strong> ${data.tujuan_peminjaman}</p>
                            <p><strong>Lokasi Penggunaan:</strong> ${data.lokasi_penggunaan || '-'}</p>
                            <p><strong>Dosen Pengampu:</strong> ${data.dosen_pengampu || '-'}</p>
                            ${data.catatan ? `<p><strong>Catatan:</strong> ${data.catatan}</p>` : ''}
                        </div>
                    </div>
                `;
            });
    });

    function kembalikanBarang(id) {
        if (confirm('Apakah barang sudah dikembalikan?')) {
            fetch(`/peminjaman/${id}/kembalikan`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                if (response.ok) {
                    location.reload();
                }
            });
        }
    }
</script>
@endpush