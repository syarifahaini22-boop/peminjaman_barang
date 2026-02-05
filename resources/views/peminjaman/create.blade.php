@extends('layouts.app')

@section('title', 'Pinjam Barang')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-black">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-hand-holding me-2"></i>Form Peminjaman Barang
                        </h5>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('peminjaman.store') }}" method="POST" id="peminjamanForm">
                            @csrf

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">
                                                <i class="fas fa-user-graduate me-2"></i>Informasi Peminjam
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <!-- PENCARIAN MAHASISWA BERDASARKAN NIM -->
                                            <div class="mb-3">
                                                <label for="nim_search" class="form-label">Cari Mahasiswa (NIM)</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="nim_search"
                                                        placeholder="Masukkan NIM mahasiswa...">
                                                    <button type="button" class="btn btn-primary" id="btnCariMahasiswa">
                                                        <i class="fas fa-search me-1"></i> Cari
                                                    </button>
                                                </div>
                                                <small class="text-muted">Masukkan NIM lengkap mahasiswa</small>
                                            </div>

                                            <!-- DATA MAHASISWA YANG DITEMUKAN - MINIMALIS -->
                                            <div id="mahasiswaInfo" class="mb-4" style="">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="mb-0 text-primary">
                                                        <i class="fas fa-user-graduate me-2"></i>Mahasiswa Terpilih
                                                    </h6>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                                        id="btnHapusMahasiswa">
                                                        <i class="fas fa-times me-1"></i> Ubah
                                                    </button>
                                                </div>

                                                <div class="border rounded p-3 bg-light">
                                                    <div class="row g-2">
                                                        <div class="col-md-4">
                                                            <div>
                                                                <small class="text-muted">Nama</small>
                                                                <div class="fw-medium" id="mahasiswaNama">-</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div>
                                                                <small class="text-muted">NIM</small>
                                                                <div class="fw-medium" id="mahasiswaNim">-</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <div>
                                                                <small class="text-muted">Jurusan</small>
                                                                <div class="fw-medium" id="mahasiswaJurusan">-</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <div>
                                                                <small class="text-muted">Status</small>
                                                                <div>
                                                                    <span class="badge bg-success">
                                                                        <i class="fas fa-check me-1"></i> Valid
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- INPUT HIDDEN UNTUK MAHASISWA ID -->
                                            <input type="hidden" name="mahasiswa_id" id="mahasiswa_id"
                                                value="{{ old('mahasiswa_id') }}">

                                            <!-- TAMPILKAN LIST MAHASISWA JIKA TIDAK PAKAI PENCARIAN -->
                                            <div class="mb-3" id="selectMahasiswa">
                                                <label class="form-label">Atau Pilih dari Daftar</label>
                                                <select class="form-select" id="mahasiswa_select">
                                                    <option value="">Pilih Mahasiswa</option>
                                                    @foreach ($mahasiswa as $mhs)
                                                        <option value="{{ $mhs->id }}" data-nim="{{ $mhs->nim }}"
                                                            data-nama="{{ $mhs->name }}"
                                                            data-jurusan="{{ $mhs->jurusan }}">
                                                            {{ $mhs->nim }} - {{ $mhs->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0">
                                                <i class="fas fa-calendar-alt me-2"></i>Informasi Waktu
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="kode_peminjaman" class="form-label">Kode Peminjaman</label>
                                                <input type="text" class="form-control" id="kode_peminjaman"
                                                    value="{{ $kode_peminjaman }}" readonly>
                                                <small class="text-muted">Kode otomatis</small>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="tanggal_peminjaman" class="form-label">Tanggal Pinjam
                                                        *</label>
                                                    <input type="date" class="form-control" id="tanggal_peminjaman"
                                                        name="tanggal_peminjaman"
                                                        value="{{ old('tanggal_peminjaman', date('Y-m-d')) }}" required>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <label for="tanggal_pengembalian" class="form-label">Tanggal Kembali
                                                        *</label>
                                                    <input type="date" class="form-control" id="tanggal_pengembalian"
                                                        name="tanggal_pengembalian"
                                                        value="{{ old('tanggal_pengembalian', date('Y-m-d', strtotime('+7 days'))) }}"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- BAGIAN PEMILIHAN BARANG -->
                            <div class="card mb-4">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-boxes me-2"></i>Daftar Barang yang Dipinjam
                                    </h6>

                                </div>
                                <div class="card-body">
                                    <!-- PENCARIAN BARANG BERDASARKAN KODE -->
                                    <div class="mb-3">
                                        <label class="form-label">Cari Barang (Kode Barang)</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="kode_barang_search"
                                                placeholder="Masukkan kode barang...">
                                            <button type="button" class="btn btn-primary" id="btnCariBarang">
                                                <i class="fas fa-search me-1"></i> Cari
                                            </button>
                                        </div>
                                        <small class="text-muted">Masukkan kode barang yang valid</small>
                                    </div>

                                    <!-- HASIL PENCARIAN BARANG -->
                                    <div id="barangSearchResult" class="mb-3"></div>

                                    <!-- TABEL BARANG YANG DIPILIH -->
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="tableBarang">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="50">No</th>
                                                    <th>Kode Barang</th>
                                                    <th>Nama Barang</th>
                                                    <th width="120">Stok Tersedia</th>
                                                    <th width="180">Jumlah Pinjam</th>
                                                </tr>
                                            </thead>
                                            <tbody id="barangList">
                                                <!-- Barang akan ditambahkan disini via JavaScript -->
                                                <tr id="emptyBarang" class="text-center text-muted">
                                                    <td colspan="5" class="py-4">
                                                        <i class="fas fa-box-open fa-2x mb-2 d-block"></i>
                                                        Belum ada barang yang dipilih
                                                        <br>
                                                        <small>Gunakan pencarian di atas untuk menambahkan barang</small>
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4" class="text-end">
                                                        <strong>Total Barang: <span id="totalBarang">0</span></strong>
                                                    </td>
                                                    <td class="text-end">
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            id="btnHapusSemua">
                                                            <i class="fas fa-trash-alt me-1"></i> Hapus Semua
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- INFORMASI TAMBAHAN -->
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>Informasi Tambahan
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="tujuan_peminjaman" class="form-label">Tujuan Peminjaman *</label>
                                        <textarea class="form-control" id="tujuan_peminjaman" name="tujuan_peminjaman" rows="2" required>{{ old('tujuan_peminjaman') }}</textarea>
                                        <small class="text-muted">Contoh: Praktikum, Belajar, Tugas Akhir, dll</small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar
                                    </a>
                                </div>
                                <div>

                                    <button type="submit" class="btn btn-primary ms-2" id="btnSubmit">
                                        <i class="fas fa-save me-1"></i>Simpan Peminjaman
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .barang-item {
            transition: all 0.3s;
            border-left: 4px solid #28a745;
        }

        .barang-item:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        #tableBarang tbody tr {
            vertical-align: middle;
        }

        #tableBarang tbody tr:hover {
            background-color: #f1f8ff;
        }

        .stok-indicator {
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        .stok-tinggi {
            background-color: #d4edda;
            color: #155724;
        }

        .stok-sedang {
            background-color: #fff3cd;
            color: #856404;
        }

        .stok-rendah {
            background-color: #f8d7da;
            color: #721c24;
        }

        .jumlah-control {
            width: 120px;
            margin: 0 auto;
        }

        .btn-jumlah {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .badge-stok {
            font-size: 0.75rem;
            padding: 3px 8px;
        }

        .empty-state {
            color: #6c757d;
            font-style: italic;
        }

        .card-header {
            background-color: #f8f9fa !important;
            border-bottom: 2px solid #dee2e6;
        }

        .input-group-jumlah {
            width: 140px;
            margin: 0 auto;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            let barangCounter = 0;
            let barangList = [];

            // ==================== PENCARIAN MAHASISWA ====================
            $('#btnCariMahasiswa').click(function() {
                const nim = $('#nim_search').val().trim();
                if (!nim) {
                    showAlert('warning', 'Peringatan!', 'Masukkan NIM mahasiswa!');
                    return;
                }

                // Cari mahasiswa berdasarkan NIM
                $.ajax({
                    url: '{{ route('mahasiswa.searchByNim') }}',
                    type: 'GET',
                    data: {
                        nim: nim
                    },
                    beforeSend: function() {
                        $('#btnCariMahasiswa').prop('disabled', true)
                            .html('<i class="fas fa-spinner fa-spin me-1"></i> Mencari...');
                    },
                    success: function(response) {
                        if (response.success && response.data) {
                            const mhs = response.data;
                            $('#mahasiswaNama').html('<strong>' + mhs.name + '</strong>');
                            $('#mahasiswaNim').html(
                                '<i class="fas fa-id-card me-1"></i> NIM: ' + mhs.nim);
                            $('#mahasiswaJurusan').html(
                                '<i class="fas fa-graduation-cap me-1"></i> Jurusan: ' + (
                                    mhs.jurusan || '-'));
                            $('#mahasiswa_id').val(mhs.id);
                            $('#mahasiswaInfo').removeClass('d-none');
                            $('#selectMahasiswa').addClass('d-none');

                            showAlert('success', 'Berhasil!', 'Mahasiswa ditemukan!');
                        } else {
                            showAlert('error', 'Gagal!', 'Mahasiswa dengan NIM ' + nim +
                                ' tidak ditemukan!');
                        }
                    },
                    error: function() {
                        showAlert('error', 'Error!',
                            'Terjadi kesalahan saat mencari mahasiswa');
                    },
                    complete: function() {
                        $('#btnCariMahasiswa').prop('disabled', false)
                            .html('<i class="fas fa-search me-1"></i> Cari');
                    }
                });
            });

            // Pilih mahasiswa dari dropdown
            $('#mahasiswa_select').change(function() {
                const selected = $(this).find('option:selected');
                if (selected.val()) {
                    $('#mahasiswaNama').html('<strong>' + selected.data('nama') + '</strong>');
                    $('#mahasiswaNim').html('<i class="fas fa-id-card me-1"></i> NIM: ' + selected.data(
                        'nim'));
                    $('#mahasiswaJurusan').html('<i class="fas fa-graduation-cap me-1"></i> Jurusan: ' + (
                        selected.data('jurusan') || '-'));
                    $('#mahasiswa_id').val(selected.val());
                    $('#mahasiswaInfo').removeClass('d-none');
                    $('#nim_search').val(selected.data('nim'));
                }
            });

            // Hapus pilihan mahasiswa
            $('#btnHapusMahasiswa').click(function() {
                $('#mahasiswaInfo').addClass('d-none');
                $('#selectMahasiswa').removeClass('d-none');
                $('#mahasiswa_id').val('');
                $('#nim_search').val('');
                $('#mahasiswa_select').val('').trigger('change');
                showAlert('info', 'Informasi', 'Pilihan mahasiswa telah dihapus');
            });

            // ==================== PENCARIAN BARANG ====================
            $('#btnCariBarang').click(function() {
                const kode = $('#kode_barang_search').val().trim();
                if (!kode) {
                    showAlert('warning', 'Peringatan!', 'Masukkan kode barang!');
                    return;
                }

                $.ajax({
                    url: '{{ route('barang.searchByKode') }}',
                    type: 'GET',
                    data: {
                        kode_barang: kode
                    },
                    beforeSend: function() {
                        $('#btnCariBarang').prop('disabled', true)
                            .html('<i class="fas fa-spinner fa-spin me-1"></i> Mencari...');
                    },
                    success: function(response) {
                        if (response.success && response.data) {
                            tampilkanHasilPencarianBarang(response.data);
                        } else {
                            $('#barangSearchResult').html(`
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Barang dengan kode "${kode}" tidak ditemukan atau tidak tersedia
                                </div>
                            `);
                        }
                    },
                    error: function() {
                        showAlert('error', 'Error!', 'Terjadi kesalahan saat mencari barang');
                    },
                    complete: function() {
                        $('#btnCariBarang').prop('disabled', false)
                            .html('<i class="fas fa-search me-1"></i> Cari');
                    }
                });
            });

            function tampilkanHasilPencarianBarang(barang) {
                // Tentukan kelas stok berdasarkan jumlah
                let stokClass = 'stok-tinggi';
                if (barang.stok_tersedia <= 3) {
                    stokClass = 'stok-rendah';
                } else if (barang.stok_tersedia <= 10) {
                    stokClass = 'stok-sedang';
                }

                let html = `
                <div class="card barang-item">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <i class="fas fa-box me-2"></i>${barang.nama}
                                </h6>
                                <div class="d-flex gap-3">
                                    <span class="text-muted">
                                        <i class="fas fa-barcode me-1"></i>Kode: ${barang.kode_barang}
                                    </span>
                                    <span class="${stokClass} stok-indicator">
                                        <i class="fas fa-boxes me-1"></i>Stok: ${barang.stok_tersedia}
                                    </span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <div class="input-group input-group-jumlah">
                                    <button class="btn btn-outline-secondary btn-sm btn-jumlah" type="button" onclick="kurangiJumlah(${barang.id})">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" 
                                           class="form-control form-control-sm text-center"
                                           id="jumlah_${barang.id}"
                                           min="1" 
                                           max="${barang.stok_tersedia}"
                                           value="1"
                                           onchange="validasiJumlah(${barang.id}, this.value, ${barang.stok_tersedia})">
                                    <button class="btn btn-outline-secondary btn-sm btn-jumlah" type="button" onclick="tambahJumlah(${barang.id}, ${barang.stok_tersedia})">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <div class="btn-group">
                                    <button type="button" 
                                            class="btn btn-sm btn-success"
                                            onclick="tambahBarangKeList(${barang.id}, '${barang.kode_barang}', '${barang.nama}', ${barang.stok_tersedia})"
                                            title="Tambahkan ke daftar pinjam">
                                        <i class="fas fa-cart-plus me-1"></i> Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `;
                $('#barangSearchResult').html(html);
                $('#kode_barang_search').val('');
            }

            // ==================== FUNGSI BANTU UNTUK JUMLAH ====================
            window.kurangiJumlah = function(barangId) {
                const input = $('#jumlah_' + barangId);
                let current = parseInt(input.val()) || 1;
                if (current > 1) {
                    input.val(current - 1);
                }
            };

            window.tambahJumlah = function(barangId, max) {
                const input = $('#jumlah_' + barangId);
                let current = parseInt(input.val()) || 1;
                if (current < max) {
                    input.val(current + 1);
                }
            };

            window.validasiJumlah = function(barangId, jumlah, max) {
                jumlah = parseInt(jumlah) || 1;
                if (jumlah < 1) {
                    $('#jumlah_' + barangId).val(1);
                    showAlert('warning', 'Peringatan!', 'Jumlah minimal 1');
                }
                if (jumlah > max) {
                    $('#jumlah_' + barangId).val(max);
                    showAlert('warning', 'Peringatan!', 'Jumlah melebihi stok tersedia!');
                }
            };

            // ==================== MANAJEMEN LIST BARANG ====================
            window.tambahBarangKeList = function(id, kode, nama, stokTersedia) {
                // Cek apakah barang sudah ada di list
                if (barangList.includes(id)) {
                    showAlert('warning', 'Peringatan!', 'Barang ini sudah ada dalam daftar pinjam!');
                    return;
                }

                const jumlah = parseInt($('#jumlah_' + id).val()) || 1;

                if (jumlah < 1) {
                    showAlert('warning', 'Peringatan!', 'Jumlah minimal 1');
                    return;
                }

                if (jumlah > stokTersedia) {
                    showAlert('warning', 'Peringatan!', `Jumlah melebihi stok tersedia! Stok: ${stokTersedia}`);
                    return;
                }

                barangCounter++;
                barangList.push(id);

                // Tentukan kelas stok
                let stokClass = 'stok-tinggi';
                if (stokTersedia <= 3) {
                    stokClass = 'stok-rendah';
                } else if (stokTersedia <= 10) {
                    stokClass = 'stok-sedang';
                }

                const row = `
                <tr id="row_${id}" class="barang-row">
                    <td class="text-center">${barangCounter}</td>
                    <td>
                        <span class="badge bg-primary">${kode}</span>
                    </td>
                    <td>${nama}</td>
                    <td class="text-center">
                        <span class="${stokClass} badge-stok">
                            <i class="fas fa-boxes me-1"></i>${stokTersedia}
                        </span>
                    </td>
                    <td class="text-center">
                        <div class="input-group input-group-jumlah">
                            <button class="btn btn-outline-secondary btn-sm btn-jumlah" type="button" onclick="updateJumlah(${id}, -1, ${stokTersedia})">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" 
                                   class="form-control form-control-sm text-center"
                                   name="barang[${id}][jumlah]"
                                   id="list_jumlah_${id}"
                                   value="${jumlah}"
                                   min="1"
                                   max="${stokTersedia}"
                                   onchange="updateJumlahInput(${id}, this.value, ${stokTersedia})"
                                   required>
                            <button class="btn btn-outline-secondary btn-sm btn-jumlah" type="button" onclick="updateJumlah(${id}, 1, ${stokTersedia})">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1">Max: ${stokTersedia}</small>
                    </td>
                </tr>
                `;

                // Sembunyikan pesan kosong jika ada
                $('#emptyBarang').hide();
                $('#barangList').append(row);
                updateTotalBarang();

                // Kosongkan hasil pencarian
                $('#barangSearchResult').html('');

                showAlert('success', 'Berhasil!', 'Barang berhasil ditambahkan ke daftar pinjam');
            };

            window.updateJumlah = function(id, delta, max) {
                const input = $('#list_jumlah_' + id);
                let current = parseInt(input.val()) || 1;
                let newValue = current + delta;

                if (newValue < 1) newValue = 1;
                if (newValue > max) {
                    showAlert('warning', 'Peringatan!', `Jumlah tidak boleh melebihi ${max}`);
                    newValue = max;
                }

                input.val(newValue);
            };

            window.updateJumlahInput = function(id, value, max) {
                let jumlah = parseInt(value) || 1;
                if (jumlah < 1) {
                    $('#list_jumlah_' + id).val(1);
                    showAlert('warning', 'Peringatan!', 'Jumlah minimal 1');
                }
                if (jumlah > max) {
                    $('#list_jumlah_' + id).val(max);
                    showAlert('warning', 'Peringatan!', `Jumlah melebihi stok tersedia! Max: ${max}`);
                }
            };

            // Hapus semua barang
            $('#btnHapusSemua').click(function() {
                if (barangList.length === 0) {
                    showAlert('info', 'Informasi', 'Tidak ada barang untuk dihapus');
                    return;
                }

                if (confirm(
                        `Apakah Anda yakin ingin menghapus semua barang (${barangList.length} barang) dari daftar?`
                    )) {
                    barangList.forEach(id => {
                        $('#row_' + id).remove();
                    });
                    barangList = [];
                    barangCounter = 0;
                    updateTotalBarang();
                    $('#emptyBarang').show();
                    showAlert('info', 'Berhasil', 'Semua barang telah dihapus dari daftar');
                }
            });

            function updateTotalBarang() {
                $('#totalBarang').text(barangList.length);
                if (barangList.length > 0) {
                    $('#btnHapusSemua').show();
                } else {
                    $('#btnHapusSemua').hide();
                }
            }

            // ==================== VALIDASI FORM ====================
            $('#peminjamanForm').submit(function(e) {
                if (barangList.length === 0) {
                    e.preventDefault();
                    showAlert('error', 'Error!', 'Pilih minimal 1 barang untuk dipinjam!');
                    $('#kode_barang_search').focus();
                    return false;
                }

                if (!$('#mahasiswa_id').val()) {
                    e.preventDefault();
                    showAlert('error', 'Error!', 'Pilih mahasiswa terlebih dahulu!');
                    $('#nim_search').focus();
                    return false;
                }

                // Validasi tanggal
                const tglPinjam = new Date($('#tanggal_peminjaman').val());
                const tglKembali = new Date($('#tanggal_pengembalian').val());

                if (tglKembali < tglPinjam) {
                    e.preventDefault();
                    showAlert('error', 'Error!',
                        'Tanggal pengembalian tidak boleh sebelum tanggal peminjaman!');
                    return false;
                }

                // Validasi semua jumlah barang
                let valid = true;
                barangList.forEach(id => {
                    const jumlah = parseInt($('#list_jumlah_' + id).val()) || 0;
                    if (jumlah < 1) {
                        valid = false;
                        showAlert('error', 'Error!', `Jumlah barang untuk ${id} tidak valid!`);
                    }
                });

                if (!valid) {
                    e.preventDefault();
                    return false;
                }

                // Tambah hidden input untuk semua barang
                barangList.forEach(id => {
                    $(this).append(`<input type="hidden" name="barang_ids[]" value="${id}">`);
                });

                // Tampilkan loading
                $('#btnSubmit').prop('disabled', true)
                    .html('<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...');

                return true;
            });

            // ==================== TANGGAL ====================
            $('#tanggal_peminjaman').change(function() {
                const minDate = $(this).val();
                $('#tanggal_pengembalian').attr('min', minDate);

                // Jika tanggal pengembalian < tanggal pinjam, reset ke min
                const currentKembali = $('#tanggal_pengembalian').val();
                if (currentKembali < minDate) {
                    $('#tanggal_pengembalian').val(minDate);
                }
            });

            // ==================== TAMBAH BARANG MANUAL ====================
            $('#btnTambahBarang').click(function() {
                $('#kode_barang_search').focus().select();
            });

            // Reset form
            $('#btnResetForm').click(function() {
                if (confirm('Apakah Anda yakin ingin mereset form? Semua data akan dihapus.')) {
                    // Reset form
                    document.getElementById('peminjamanForm').reset();

                    // Reset data barang
                    barangList.forEach(id => {
                        $('#row_' + id).remove();
                    });
                    barangList = [];
                    barangCounter = 0;
                    updateTotalBarang();
                    $('#emptyBarang').show();

                    // Reset mahasiswa
                    $('#mahasiswaInfo').addClass('d-none');
                    $('#selectMahasiswa').removeClass('d-none');
                    $('#mahasiswa_id').val('');
                    $('#nim_search').val('');
                    $('#mahasiswa_select').val('').trigger('change');

                    // Reset pencarian barang
                    $('#barangSearchResult').html('');
                    $('#kode_barang_search').val('');

                    showAlert('info', 'Form Reset', 'Form telah direset ke keadaan awal');
                }
            });

            // ==================== FUNGSI BANTU ====================
            function showAlert(type, title, message) {
                // Buat alert dinamis
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        <strong>${title}</strong> ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;

                // Tambahkan alert di atas form
                $('#peminjamanForm').prepend(alertHtml);

                // Hapus otomatis setelah 5 detik
                setTimeout(() => {
                    $('.alert').alert('close');
                }, 5000);
            }

            // Focus ke pencarian barang saat halaman load
            $('#kode_barang_search').focus();
        });
    </script>
@endpush
