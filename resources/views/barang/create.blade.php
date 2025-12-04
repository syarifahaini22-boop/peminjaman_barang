@extends('layouts.app')

@section('title', 'Tambah Barang Baru')
@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="navbar-custom mb-4">
            <div>
                <h2 class="h3 mb-1">
                    <i class="bi bi-plus-circle me-2"></i> Tambah Barang Baru
                </h2>
                <p class="text-muted mb-0">Isi form berikut untuk menambahkan barang baru</p>
            </div>
            <a href="{{ route('barang.index') }}" class="btn btn-custom btn-custom-secondary">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>

        <!-- Form Create -->
        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <h5 class="mb-0">
                    <i class="bi bi-box me-2"></i> Form Tambah Barang
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="kode_barang" class="form-label form-label-custom">Kode Barang *</label>
                            <input type="text"
                                class="form-control form-control-custom @error('kode_barang') is-invalid @enderror"
                                id="kode_barang" name="kode_barang" value="{{ old('kode_barang') }}" required
                                placeholder="Contoh: ELK-001, LAB-015">
                            @error('kode_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Kode unik untuk identifikasi barang</small>
                        </div>

                        <div class="col-md-8 mb-3">
                            <label for="nama" class="form-label form-label-custom">Nama Barang *</label>
                            <input type="text"
                                class="form-control form-control-custom @error('nama') is-invalid @enderror" id="nama"
                                name="nama" value="{{ old('nama') }}" required
                                placeholder="Masukkan nama lengkap barang">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="kategori" class="form-label form-label-custom">Kategori *</label>
                            <select class="form-select form-control-custom @error('kategori') is-invalid @enderror"
                                id="kategori" name="kategori" required>
                                <option value="">Pilih Kategori</option>
                                <option value="elektronik" {{ old('kategori') == 'elektronik' ? 'selected' : '' }}>
                                    Elektronik</option>
                                <option value="alat_lab" {{ old('kategori') == 'alat_lab' ? 'selected' : '' }}>Alat
                                    Laboratorium</option>
                                <option value="buku" {{ old('kategori') == 'buku' ? 'selected' : '' }}>Buku & Referensi
                                </option>
                                <option value="perlengkapan" {{ old('kategori') == 'perlengkapan' ? 'selected' : '' }}>
                                    Perlengkapan</option>
                            </select>
                            @error('kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="merek" class="form-label form-label-custom">Merek</label>
                            <input type="text"
                                class="form-control form-control-custom @error('merek') is-invalid @enderror" id="merek"
                                name="merek" value="{{ old('merek') }}" placeholder="Contoh: Asus, Fluke, dll">
                            @error('merek')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="lokasi" class="form-label form-label-custom">Lokasi Penyimpanan *</label>
                            <input type="text"
                                class="form-control form-control-custom @error('lokasi') is-invalid @enderror"
                                id="lokasi" name="lokasi" value="{{ old('lokasi') }}"
                                placeholder="Contoh: Rak A1, Lemari B3">
                            @error('lokasi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="status" class="form-label form-label-custom">Status *</label>
                            <select class="form-select form-control-custom @error('status') is-invalid @enderror"
                                id="status" name="status">
                                <option value="tersedia" {{ old('status') == 'tersedia' ? 'selected' : '' }}>Tersedia
                                </option>
                                <option value="dipinjam" {{ old('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam
                                </option>
                                <option value="rusak" {{ old('status') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                                <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>
                                    Maintenance</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="kondisi" class="form-label form-label-custom">Kondisi *</label>
                            <select class="form-select form-control-custom @error('kondisi') is-invalid @enderror"
                                id="kondisi" name="kondisi" required>
                                <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak_ringan" {{ old('kondisi') == 'rusak_ringan' ? 'selected' : '' }}>Rusak
                                    Ringan</option>
                                <option value="rusak_berat" {{ old('kondisi') == 'rusak_berat' ? 'selected' : '' }}>Rusak
                                    Berat</option>
                            </select>
                            @error('kondisi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="tahun_pengadaan" class="form-label form-label-custom">Tahun Pengadaan</label>
                            <input type="number"
                                class="form-control form-control-custom @error('tahun_pengadaan') is-invalid @enderror"
                                id="tahun_pengadaan" name="tahun_pengadaan" value="{{ old('tahun_pengadaan') }}"
                                min="2000" max="{{ date('Y') }}" placeholder="Contoh: 2023">
                            @error('tahun_pengadaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="deskripsi" class="form-label form-label-custom">Deskripsi</label>
                            <textarea class="form-control form-control-custom @error('deskripsi') is-invalid @enderror" id="deskripsi"
                                name="deskripsi" rows="4" placeholder="Deskripsi detail tentang barang">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="gambar" class="form-label form-label-custom">Gambar Barang</label>
                            <input type="file"
                                class="form-control form-control-custom @error('gambar') is-invalid @enderror"
                                id="gambar" name="gambar" accept="image/*">
                            @error('gambar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="bi bi-info-circle me-1"></i> Format: JPG, PNG. Maks: 2MB
                                </small>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-flex justify-content-between align-items-center mt-5 pt-4 border-top">
                        <div>
                            <button type="reset" class="btn btn-custom btn-custom-outline">
                                <i class="bi bi-eraser me-1"></i> Reset Form
                            </button>
                        </div>
                        <div class="btn-group">
                            <a href="{{ route('barang.index') }}" class="btn btn-custom btn-custom-secondary me-2">
                                <i class="bi bi-x-circle me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-custom btn-custom-primary">
                                <i class="bi bi-save me-1"></i> Simpan Barang
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Style Tambahan untuk Form -->
    <style>
        /* Form Lebar Full */
        .card-body {
            padding: 30px;
        }

        /* Input yang lebih besar */
        .form-control-custom {
            padding: 12px 15px;
            font-size: 1rem;
        }

        /* Label yang lebih jelas */
        .form-label-custom {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 0.95rem;
        }

        /* Validasi error lebih jelas */
        .is-invalid {
            border-color: #e74c3c !important;
        }

        .invalid-feedback {
            display: block;
            margin-top: 5px;
            color: #e74c3c;
            font-size: 0.875rem;
        }

        /* Card form lebih lebar */
        .card-custom {
            border-radius: 15px;
        }

        /* Spacing yang lebih baik */
        .mb-3 {
            margin-bottom: 1.5rem !important;
        }

        /* Tombol yang lebih besar */
        .btn-custom {
            padding: 12px 25px;
            font-size: 1rem;
            border-radius: 10px;
        }
    </style>
@endsection
