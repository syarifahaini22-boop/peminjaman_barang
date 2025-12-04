@extends('layouts.app')

@section('title', 'Pinjam Barang')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-hand-holding"></i> Form Peminjaman Barang
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- TAMPILKAN ERROR UMUM -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('peminjaman.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="kode_peminjaman" class="form-label">Kode Peminjaman</label>
                                    <input type="text" class="form-control" id="kode_peminjaman"
                                        value="{{ $kode_peminjaman }}" readonly>
                                    <small class="text-muted">Kode otomatis</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="barang_id" class="form-label">Barang *</label>
                                    <select class="form-select select2 @error('barang_id') is-invalid @enderror"
                                        id="barang_id" name="barang_id" required>
                                        <option value="">Pilih Barang</option>
                                        @foreach ($barang as $item)
                                            <option value="{{ $item->id }}" data-stok="{{ $item->stok_tersedia }}"
                                                {{ old('barang_id') == $item->id ? 'selected' : '' }}>
                                                {{ $item->nama }} (Stok: {{ $item->stok_tersedia }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('barang_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="mahasiswa_id" class="form-label">Mahasiswa *</label>
                                    <select class="form-select select2 @error('mahasiswa_id') is-invalid @enderror"
                                        id="mahasiswa_id" name="mahasiswa_id" required>
                                        <option value="">Pilih Mahasiswa</option>
                                        @foreach ($mahasiswa as $mhs)
                                            <option value="{{ $mhs->id }}"
                                                {{ old('mahasiswa_id') == $mhs->id ? 'selected' : '' }}>
                                                {{ $mhs->name }} - {{ $mhs->email }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mahasiswa_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_peminjaman" class="form-label">Tanggal Peminjaman *</label>
                                    <input type="date"
                                        class="form-control @error('tanggal_peminjaman') is-invalid @enderror"
                                        id="tanggal_peminjaman" name="tanggal_peminjaman"
                                        value="{{ old('tanggal_peminjaman', date('Y-m-d')) }}" required>
                                    @error('tanggal_peminjaman')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_pengembalian" class="form-label">Tanggal Pengembalian *</label>
                                    <input type="date"
                                        class="form-control @error('tanggal_pengembalian') is-invalid @enderror"
                                        id="tanggal_pengembalian" name="tanggal_pengembalian"
                                        value="{{ old('tanggal_pengembalian', date('Y-m-d', strtotime('+7 days'))) }}"
                                        required>
                                    @error('tanggal_pengembalian')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>



                                <div class="col-md-12 mb-3">
                                    <label for="tujuan_peminjaman" class="form-label">Tujuan Peminjaman *</label>
                                    <textarea class="form-control @error('tujuan_peminjaman') is-invalid @enderror" id="tujuan_peminjaman"
                                        name="tujuan_peminjaman" rows="2" required>{{ old('tujuan_peminjaman') }}</textarea>
                                    <small class="text-muted">Contoh: Praktikum, Penelitian, Tugas Akhir, dll</small>
                                    @error('tujuan_peminjaman')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="lokasi_penggunaan" class="form-label">Lokasi Penggunaan</label>
                                    <input type="text"
                                        class="form-control @error('lokasi_penggunaan') is-invalid @enderror"
                                        id="lokasi_penggunaan" name="lokasi_penggunaan"
                                        value="{{ old('lokasi_penggunaan') }}"
                                        placeholder="Contoh: Lab Komputer, Ruang Kelas, dll">
                                    @error('lokasi_penggunaan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="catatan" class="form-label">Catatan</label>
                                    <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="2">{{ old('catatan') }}</textarea>
                                    <small class="text-muted">Catatan tambahan untuk peminjaman</small>
                                    @error('catatan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Peminjaman
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Update stok info saat barang dipilih
            $('#barang_id').change(function() {
                var selected = $(this).find('option:selected');
                var stok = selected.data('stok') || 0;
                // Pastikan ada element dengan id stok-info
                if ($('#stok-info').length) {
                    $('#stok-info').text('Stok tersedia: ' + stok);
                }
            });

            // Set min tanggal untuk tanggal pengembalian
            $('#tanggal_peminjaman').change(function() {
                $('#tanggal_pengembalian').attr('min', $(this).val());
            });

            // Inisialisasi nilai awal untuk stok info
            $('#barang_id').trigger('change');
        });
    </script>
@endpush
