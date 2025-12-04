@extends('layouts.app')

@section('title', 'Edit Barang')
@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-custom">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="bi bi-pencil-square me-2"></i> Edit Barang
                        </h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">

                            <!-- Di bagian bawah form -->
                            <div class="d-flex justify-content-between mt-4">

                                <div class="btn-group">

                                </div>
                            </div>
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="kode_barang" class="form-label">Kode Barang *</label>
                                    <input type="text" class="form-control @error('kode_barang') is-invalid @enderror"
                                        id="kode_barang" name="kode_barang"
                                        value="{{ old('kode_barang', $barang->kode_barang) }}" required>
                                    @error('kode_barang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Kode unik barang</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="nama_barang" class="form-label">Nama Barang *</label>
                                    <input type="text" class="form-control @error('nama_barang') is-invalid @enderror"
                                        id="nama_barang" name="nama_barang"
                                        value="{{ old('nama_barang', $barang->nama_barang) }}" required>
                                    @error('nama_barang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="kategori" class="form-label">Kategori *</label>
                                    <select class="form-select @error('kategori') is-invalid @enderror" id="kategori"
                                        name="kategori" required>
                                        <option value="">Pilih Kategori</option>
                                        <option value="elektronik"
                                            {{ old('kategori', $barang->kategori) == 'elektronik' ? 'selected' : '' }}>
                                            Elektronik</option>
                                        <option value="alat_lab"
                                            {{ old('kategori', $barang->kategori) == 'alat_lab' ? 'selected' : '' }}>Alat
                                            Lab</option>
                                        <option value="buku"
                                            {{ old('kategori', $barang->kategori) == 'buku' ? 'selected' : '' }}>Buku
                                        </option>
                                        <option value="perlengkapan"
                                            {{ old('kategori', $barang->kategori) == 'perlengkapan' ? 'selected' : '' }}>
                                            Perlengkapan</option>
                                    </select>
                                    @error('kategori')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="merek" class="form-label">Merek</label>
                                    <input type="text" class="form-control @error('merek') is-invalid @enderror"
                                        id="merek" name="merek" value="{{ old('merek', $barang->merek) }}">
                                    @error('merek')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="lokasi" class="form-label">Lokasi Penyimpanan *</label>
                                    <input type="text" class="form-control @error('lokasi') is-invalid @enderror"
                                        id="lokasi" name="lokasi" value="{{ old('lokasi', $barang->lokasi) }}"
                                        required>
                                    @error('lokasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Contoh: Rak A1, Lemari B3</small>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="status" class="form-label">Status *</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="tersedia"
                                            {{ old('status', $barang->status) == 'tersedia' ? 'selected' : '' }}>Tersedia
                                        </option>
                                        <option value="dipinjam"
                                            {{ old('status', $barang->status) == 'dipinjam' ? 'selected' : '' }}>Dipinjam
                                        </option>
                                        <option value="rusak"
                                            {{ old('status', $barang->status) == 'rusak' ? 'selected' : '' }}>Rusak
                                        </option>
                                        <option value="maintenance"
                                            {{ old('status', $barang->status) == 'maintenance' ? 'selected' : '' }}>
                                            Maintenance</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="kondisi" class="form-label">Kondisi *</label>
                                    <select class="form-select @error('kondisi') is-invalid @enderror" id="kondisi"
                                        name="kondisi" required>
                                        <option value="baik"
                                            {{ old('kondisi', $barang->kondisi) == 'baik' ? 'selected' : '' }}>Baik
                                        </option>
                                        <option value="rusak_ringan"
                                            {{ old('kondisi', $barang->kondisi) == 'rusak_ringan' ? 'selected' : '' }}>
                                            Rusak Ringan</option>
                                        <option value="rusak_berat"
                                            {{ old('kondisi', $barang->kondisi) == 'rusak_berat' ? 'selected' : '' }}>Rusak
                                            Berat</option>
                                    </select>
                                    @error('kondisi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tahun_pengadaan" class="form-label">Tahun Pengadaan</label>
                                    <input type="number"
                                        class="form-control @error('tahun_pengadaan') is-invalid @enderror"
                                        id="tahun_pengadaan" name="tahun_pengadaan"
                                        value="{{ old('tahun_pengadaan', $barang->tahun_pengadaan) }}" min="2000"
                                        max="{{ date('Y') }}">
                                    @error('tahun_pengadaan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="gambar" class="form-label">Gambar Barang</label>
                                    <input type="file" class="form-control @error('gambar') is-invalid @enderror"
                                        id="gambar" name="gambar" accept="image/*">
                                    @error('gambar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @if ($barang->gambar)
                                        <small class="text-muted d-block mt-1">
                                            <i class="bi bi-image me-1"></i> Gambar saat ini:
                                            <a href="{{ asset('storage/' . $barang->gambar) }}" target="_blank">Lihat</a>
                                        </small>
                                    @endif
                                    <small class="text-muted">Format: JPG, PNG. Maks: 2MB</small>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-4">
                                <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save me-1"></i> Update Barang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
