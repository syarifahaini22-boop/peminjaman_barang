@extends('layouts.app')

@section('title', 'Pinjam Barang')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header bg-primary text-white">
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
                                                        <i class="fas fa-search"></i> Cari
                                                    </button>
                                                </div>
                                                <small class="text-muted">Masukkan NIM lengkap mahasiswa</small>
                                            </div>

                                            <!-- DATA MAHASISWA YANG DITEMUKAN -->
                                            <div id="mahasiswaInfo" class="alert alert-info d-none">
                                                <div class="d-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h6 class="mb-1" id="mahasiswaNama"></h6>
                                                        <p class="mb-1" id="mahasiswaNim"></p>
                                                        <p class="mb-0" id="mahasiswaJurusan"></p>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        id="btnHapusMahasiswa">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- INPUT HIDDEN UNTUK MAHASISWA ID -->
                                            <input type="hidden" name="mahasiswa_id" id="mahasiswa_id"
                                                value="{{ old('mahasiswa_id') }}">

                                            <!-- TAMPILKAN LIST MAHASISWA JIKA TIDAK PAKAI PENCARIAN -->
                                            <div class="mb-3" id="selectMahasiswa">
                                                <label class="form-label">Atau Pilih dari Daftar</label>
                                                <select class="form-select select2" id="mahasiswa_select">
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
                                    <button type="button" class="btn btn-sm btn-success" id="btnTambahBarang">
                                        <i class="fas fa-plus me-1"></i>Tambah Barang
                                    </button>
                                </div>
                                <div class="card-body">
                                    <!-- PENCARIAN BARANG BERDASARKAN KODE -->
                                    <div class="mb-3">
                                        <label class="form-label">Cari Barang (Kode Barang)</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="kode_barang_search"
                                                placeholder="Masukkan kode barang...">
                                            <button type="button" class="btn btn-primary" id="btnCariBarang">
                                                <i class="fas fa-search"></i> Cari
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

                                                    <th width="150">Jumlah Pinjam</th>
                                                    <th width="50">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="barangList">
                                                <!-- Barang akan ditambahkan disini via JavaScript -->
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="6" class="text-end">
                                                        <strong>Total Barang: <span id="totalBarang">0</span></strong>
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
                                        <small class="text-muted">Contoh: Praktikum, Penelitian, Tugas Akhir, dll</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="lokasi_penggunaan" class="form-label">Lokasi Penggunaan</label>
                                        <input type="text" class="form-control" id="lokasi_penggunaan"
                                            name="lokasi_penggunaan" value="{{ old('lokasi_penggunaan') }}"
                                            placeholder="Contoh: Lab Komputer, Ruang Kelas, dll">
                                    </div>

                                    <div class="mb-3">
                                        <label for="catatan" class="form-label">Catatan</label>
                                        <textarea class="form-control" id="catatan" name="catatan" rows="2">{{ old('catatan') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-primary" id="btnSubmit">
                                    <i class="fas fa-save me-1"></i>Simpan Peminjaman
                                </button>
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
        }

        .barang-item:hover {
            background-color: #f8f9fa;
        }

        #tableBarang tbody tr {
            vertical-align: middle;
        }

        .btn-remove {
            padding: 0.25rem 0.5rem;
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
                    alert('Masukkan NIM mahasiswa!');
                    return;
                }

                // Cari mahasiswa berdasarkan NIM
                $.ajax({
                    url: '{{ route('mahasiswa.searchByNim') }}',
                    type: 'GET',
                    data: {
                        nim: nim
                    },
                    success: function(response) {
                        if (response.success && response.data) {
                            const mhs = response.data;
                            $('#mahasiswaNama').text(mhs.name);
                            $('#mahasiswaNim').text('NIM: ' + mhs.nim);
                            $('#mahasiswaJurusan').text('Jurusan: ' + (mhs.jurusan || '-'));
                            $('#mahasiswa_id').val(mhs.id);
                            $('#mahasiswaInfo').removeClass('d-none');
                            $('#selectMahasiswa').addClass('d-none');
                        } else {
                            alert('Mahasiswa dengan NIM ' + nim + ' tidak ditemukan!');
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat mencari mahasiswa');
                    }
                });
            });

            // Pilih mahasiswa dari dropdown
            $('#mahasiswa_select').change(function() {
                const selected = $(this).find('option:selected');
                if (selected.val()) {
                    $('#mahasiswaNama').text(selected.data('nama'));
                    $('#mahasiswaNim').text('NIM: ' + selected.data('nim'));
                    $('#mahasiswaJurusan').text('Jurusan: ' + (selected.data('jurusan') || '-'));
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
            });

            // ==================== PENCARIAN BARANG ====================
            $('#btnCariBarang').click(function() {
                const kode = $('#kode_barang_search').val().trim();
                if (!kode) {
                    alert('Masukkan kode barang!');
                    return;
                }

                $.ajax({
                    url: '{{ route('barang.searchByKode') }}',
                    type: 'GET',
                    data: {
                        kode_barang: kode
                    },
                    success: function(response) {
                        if (response.success && response.data) {
                            tampilkanHasilPencarianBarang(response.data);
                        } else {
                            $('#barangSearchResult').html(
                                '<div class="alert alert-warning">Barang dengan kode "' +
                                kode + '" tidak ditemukan</div>'
                            );
                        }
                    },
                    error: function() {
                        alert('Terjadi kesalahan saat mencari barang');
                    }
                });
            });

            function tampilkanHasilPencarianBarang(barang) {
                let html = `
                <div class="card barang-item">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">${barang.nama}</h6>
                                <p class="mb-1 text-muted">Kode: ${barang.kode_barang}</p>
                                <p class="mb-0">Stok Tersedia: <span class="badge bg-info">${barang.stok_tersedia}</span></p>
                            </div>
                            <div class="d-flex align-items-center">
                                <input type="number" 
                                       class="form-control form-control-sm me-2" 
                                       id="jumlah_${barang.id}"
                                       min="1" 
                                       max="${barang.stok_tersedia}"
                                       value="1"
                                       style="width: 80px;">
                                <button type="button" 
                                        class="btn btn-sm btn-success"
                                        onclick="tambahBarangKeList(${barang.id}, '${barang.kode_barang}', '${barang.nama}', ${barang.stok_tersedia})">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
                $('#barangSearchResult').html(html);
                $('#kode_barang_search').val('');
            }

            // ==================== MANAJEMEN LIST BARANG ====================
            window.tambahBarangKeList = function(id, kode, nama, stokTersedia) {
                // Cek apakah barang sudah ada di list
                if (barangList.includes(id)) {
                    alert('Barang ini sudah ada dalam daftar!');
                    return;
                }

                const jumlah = parseInt($('#jumlah_' + id).val()) || 1;

                if (jumlah < 1) {
                    alert('Jumlah minimal 1');
                    return;
                }

                if (jumlah > stokTersedia) {
                    alert('Jumlah melebihi stok tersedia!');
                    return;
                }

                barangCounter++;
                barangList.push(id);

                const row = `
                <tr id="row_${id}">
                    <td>${barangCounter}</td>
                    <td>${kode}</td>
                    <td>${nama}</td>
                    <td>${stokTersedia}</td>
                    <td>
                        <input type="number" 
                               class="form-control form-control-sm jumlah-barang"
                               name="barang[${id}][jumlah]"
                               value="${jumlah}"
                               min="1"
                               max="${stokTersedia}"
                               onchange="updateJumlah(${id}, this.value, ${stokTersedia})"
                               required>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger btn-remove" onclick="hapusBarangDariList(${id})">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
            `;

                $('#barangList').append(row);
                updateTotalBarang();
            };

            window.updateJumlah = function(id, jumlah, stokTersedia) {
                jumlah = parseInt(jumlah);
                if (jumlah < 1) {
                    alert('Jumlah minimal 1');
                    $(`input[name="barang[${id}][jumlah]"]`).val(1);
                    return;
                }
                if (jumlah > stokTersedia) {
                    alert('Jumlah melebihi stok tersedia!');
                    $(`input[name="barang[${id}][jumlah]"]`).val(stokTersedia);
                    return;
                }
            };

            window.hapusBarangDariList = function(id) {
                $('#row_' + id).remove();
                barangList = barangList.filter(item => item !== id);

                // Update nomor urut
                $('#barangList tr').each(function(index) {
                    $(this).find('td:first').text(index + 1);
                });

                barangCounter = barangList.length;
                updateTotalBarang();
            };

            function updateTotalBarang() {
                $('#totalBarang').text(barangList.length);
            }

            // ==================== VALIDASI FORM ====================
            $('#peminjamanForm').submit(function(e) {
                if (barangList.length === 0) {
                    e.preventDefault();
                    alert('Pilih minimal 1 barang!');
                    return false;
                }

                if (!$('#mahasiswa_id').val()) {
                    e.preventDefault();
                    alert('Pilih mahasiswa terlebih dahulu!');
                    return false;
                }

                // Validasi tanggal
                const tglPinjam = new Date($('#tanggal_peminjaman').val());
                const tglKembali = new Date($('#tanggal_pengembalian').val());

                if (tglKembali < tglPinjam) {
                    e.preventDefault();
                    alert('Tanggal pengembalian tidak boleh sebelum tanggal peminjaman!');
                    return false;
                }

                // Tambah hidden input untuk semua barang
                barangList.forEach(id => {
                    $(this).append(`<input type="hidden" name="barang_ids[]" value="${id}">`);
                });

                return true;
            });

            // ==================== TANGGAL ====================
            $('#tanggal_peminjaman').change(function() {
                $('#tanggal_pengembalian').attr('min', $(this).val());
            });

            // ==================== TAMBAH BARANG MANUAL ====================
            $('#btnTambahBarang').click(function() {
                $('#kode_barang_search').focus();
            });
        });
    </script>
@endpush
