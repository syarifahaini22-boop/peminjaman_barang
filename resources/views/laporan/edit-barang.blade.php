@extends('layouts.app')

@section('title', 'Edit Data Barang')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit"></i> Edit Data Barang
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="kode_barang" class="form-label">Kode Barang</label>
                                <input type="text" class="form-control" 
                                       value="{{ $barang->kode_barang }}" readonly>
                                <small class="text-muted">Kode tidak dapat diubah</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="nama" class="form-label">Nama Barang *</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                       id="nama" name="nama" value="{{ old('nama', $barang->nama) }}" required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="kategori" class="form-label">Kategori *</label>
                                <select class="form-select @error('kategori') is-invalid @enderror" 
                                        id="kategori" name="kategori" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Elektronik" {{ $barang->kategori == 'Elektronik' ? 'selected' : '' }}>Elektronik</option>
                                    <option value="Buku" {{ $barang->kategori == 'Buku' ? 'selected' : '' }}>Buku</option>
                                    <option value="Alat Laboratorium" {{ $barang->kategori == 'Alat Laboratorium' ? 'selected' : '' }}>Alat Laboratorium</option>
                                    <option value="Furniture" {{ $barang->kategori == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                                    <option value="Lainnya" {{ $barang->kategori == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('kategori')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="stok" class="form-label">Stok *</label>
                                <input type="number" class="form-control @error('stok') is-invalid @enderror" 
                                       id="stok" name="stok" min="0" value="{{ old('stok', $barang->stok) }}" required>
                                @error('stok')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="lokasi" class="form-label">Lokasi</label>
                                <input type="text" class="form-control @error('lokasi') is-invalid @enderror" 
                                       id="lokasi" name="lokasi" value="{{ old('lokasi', $barang->lokasi) }}">
                                @error('lokasi')
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
                                @if($barang->gambar)
                                    <small class="text-muted">Gambar saat ini: 
                                        <a href="{{ asset('storage/' . $barang->gambar) }}" target="_blank">Lihat</a>
                                    </small>
                                @endif
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                          id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('barang.show', $barang->id) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i> Lihat Detail
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Statistik Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar"></i> Statistik Peminjaman Barang Ini
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <h3 class="text-primary">{{ $stats['total_peminjaman'] }}</h3>
                            <p class="text-muted mb-0">Total Dipinjam</p>
                        </div>
                        <div class="col-md-4">
                            <h3 class="text-warning">{{ $stats['sedang_dipinjam'] }}</h3>
                            <p class="text-muted mb-0">Sedang Dipinjam</p>
                        </div>
                        <div class="col-md-4">
                            <h3 class="text-success">{{ $stats['stok_tersedia'] }}</h3>
                            <p class="text-muted mb-0">Stok Tersedia</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection