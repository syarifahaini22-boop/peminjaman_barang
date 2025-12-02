@extends('layouts.app')

@section('title', 'Data Barang')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h4">Data Barang Lab RSI</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahBarangModal">
            <i class="bi bi-plus-circle"></i> Tambah Barang
        </button>
    </div>

    <!-- Filter dan Search -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <select class="form-select" id="filterKategori">
                        <option value="">Semua Kategori</option>
                        <option value="elektronik">Elektronik</option>
                        <option value="alat_lab">Alat Lab</option>
                        <option value="buku">Buku</option>
                        <option value="perlengkapan">Perlengkapan</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="tersedia">Tersedia</option>
                        <option value="dipinjam">Dipinjam</option>
                        <option value="rusak">Rusak</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Cari barang..." id="searchBarang">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabel Barang -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="tableBarang">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Merek</th>
                            <th>Lokasi</th>
                            <th>Status</th>
                            <th>Kondisi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barang as $item)
                        <tr>
                            <td><strong>{{ $item->kode_barang }}</strong></td>
                            <td>{{ $item->nama_barang }}</td>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($item->kategori) }}</span>
                            </td>
                            <td>{{ $item->merek ?? '-' }}</td>
                            <td>{{ $item->lokasi }}</td>
                            <td>
                                @if($item->status == 'tersedia')
                                    <span class="badge bg-success">Tersedia</span>
                                @elseif($item->status == 'dipinjam')
                                    <span class="badge bg-warning">Dipinjam</span>
                                @else
                                    <span class="badge bg-danger">Rusak</span>
                                @endif
                            </td>
                            <td>
                                @if($item->kondisi == 'baik')
                                    <span class="text-success"><i class="bi bi-check-circle"></i> Baik</span>
                                @else
                                    <span class="text-warning"><i class="bi bi-exclamation-triangle"></i> {{ $item->kondisi }}</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" title="Pinjam" data-bs-toggle="modal" data-bs-target="#pinjamModal" data-id="{{ $item->id }}">
                                    <i class="bi bi-calendar-plus"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-info" title="Detail" data-bs-toggle="modal" data-bs-target="#detailModal" data-id="{{ $item->id }}">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-warning" title="Edit" data-bs-toggle="modal" data-bs-target="#editModal" data-id="{{ $item->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-sm btn-outline-danger" title="Hapus" onclick="confirmDelete({{ $item->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $barang->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Barang -->
<div class="modal fade" id="tambahBarangModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Barang Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('barang.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Barang *</label>
                        <input type="text" class="form-control" name="nama_barang" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori *</label>
                            <select class="form-select" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="elektronik">Elektronik</option>
                                <option value="alat_lab">Alat Lab</option>
                                <option value="buku">Buku</option>
                                <option value="perlengkapan">Perlengkapan</option>
                                <option value="lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kode Barang *</label>
                            <input type="text" class="form-control" name="kode_barang" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Merek</label>
                            <input type="text" class="form-control" name="merek">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun Pengadaan</label>
                            <input type="number" class="form-control" name="tahun_pengadaan" min="2000" max="{{ date('Y') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi Penyimpanan *</label>
                        <input type="text" class="form-control" name="lokasi" required placeholder="Contoh: Rak A1, Lemari B3">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status *</label>
                            <select class="form-select" name="status" required>
                                <option value="tersedia">Tersedia</option>
                                <option value="rusak">Rusak</option>
                                <option value="maintenance">Maintenance</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kondisi *</label>
                            <select class="form-select" name="kondisi" required>
                                <option value="baik">Baik</option>
                                <option value="rusak_ringan">Rusak Ringan</option>
                                <option value="rusak_berat">Rusak Berat</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Pinjam Barang -->
<div class="modal fade" id="pinjamModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Peminjaman Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('peminjaman.store') }}" method="POST">
                @csrf
                <input type="hidden" name="barang_id" id="barang_id_pinjam">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Mahasiswa *</label>
                        <select class="form-select" name="mahasiswa_id" required>
                            <option value="">Pilih Mahasiswa</option>
                            <!-- Data mahasiswa akan diisi via AJAX -->
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Peminjaman *</label>
                            <input type="date" class="form-control" name="tanggal_peminjaman" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tanggal Pengembalian *</label>
                            <input type="date" class="form-control" name="tanggal_pengembalian" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tujuan Peminjaman *</label>
                        <textarea class="form-control" name="tujuan_peminjaman" rows="2" required placeholder="Contoh: Praktikum Jaringan Komputer"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Lokasi Penggunaan</label>
                        <input type="text" class="form-control" name="lokasi_penggunaan" placeholder="Contoh: Lab RSI Gedung 3">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Dosen Pengampu (jika ada)</label>
                        <input type="text" class="form-control" name="dosen_pengampu">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Peminjaman</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Filter dan Search
    document.getElementById('searchBarang').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#tableBarang tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });

    // Pinjam Modal
    const pinjamModal = document.getElementById('pinjamModal');
    pinjamModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const barangId = button.getAttribute('data-id');
        document.getElementById('barang_id_pinjam').value = barangId;
    });

    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus barang ini?')) {
            // AJAX delete atau form submit
            fetch(`/barang/${id}`, {
                method: 'DELETE',
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