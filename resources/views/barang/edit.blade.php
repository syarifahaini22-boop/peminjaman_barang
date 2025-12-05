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
                                    <label for="nama" class="form-label">Nama Barang *</label>
                                    <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                        id="nama" name="nama" value="{{ old('nama', $barang->nama) }}" required>
                                    @error('nama')
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
                                    <label for="stok" class="form-label">Stok *</label>
                                    <input type="number" class="form-control @error('stok') is-invalid @enderror"
                                        id="stok" name="stok" value="{{ old('stok', $barang->stok) }}"
                                        min="0" required>
                                    @error('stok')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Jumlah barang yang tersedia</small>
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
                                        id="lokasi" name="lokasi" value="{{ old('lokasi', $barang->lokasi) }}">
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



                            @if ($barang->gambar)
                                <div class="col-md-6">
                                    <label class="form-label">Gambar Saat Ini</label>
                                    <div>
                                        <img src="{{ asset('storage/' . $barang->gambar) }}"
                                            alt="Gambar {{ $barang->nama }}" class="img-thumbnail"
                                            style="max-height: 150px;">
                                        <div class="form-check mt-2">
                                            <input type="checkbox" class="form-check-input" id="hapus_gambar"
                                                name="hapus_gambar">
                                            <label class="form-check-label text-danger" for="hapus_gambar">
                                                Hapus gambar saat ini
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('barang.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <div>

                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Update Barang
                            </button>
                        </div>
                    </div>
                    </form>
                </div>

                @push('scripts')
                    <script>
                        function resetForm() {
                            if (confirm('Apakah Anda yakin ingin mengembalikan form ke nilai awal?')) {
                                // Reset form ke nilai awal
                                const form = document.querySelector('form');
                                form.reset();



                                document.getElementById('kategori').value = originalValues.kategori;
                                document.getElementById('status').value = originalValues.status;
                                document.getElementById('kondisi').value = originalValues.kondisi;
                            }
                        }

                        // Validasi stok
                        document.getElementById('stok').addEventListener('change', function() {
                            if (this.value < 0) {
                                alert('Stok tidak boleh negatif');
                                this.value = 0;
                            }
                        });

                        // Preview gambar sebelum upload
                        document.getElementById('gambar').addEventListener('change', function(e) {
                            const file = e.target.files[0];
                            if (file) {
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    // Create preview if doesn't exist
                                    let preview = document.getElementById('image-preview');
                                    if (!preview) {
                                        preview = document.createElement('div');
                                        preview.id = 'image-preview';
                                        preview.className = 'mt-2';
                                        document.getElementById('gambar').parentNode.appendChild(preview);
                                    }

                                    preview.innerHTML = `
                    <div class="alert alert-info p-2">
                        <small><i class="bi bi-info-circle me-1"></i> Preview gambar baru:</small>
                        <div class="mt-2">
                            <img src="${e.target.result}" class="img-thumbnail" style="max-height: 100px;">
                            <div class="mt-1">
                                <small>Nama: ${file.name}</small><br>
                                <small>Ukuran: ${(file.size / 1024).toFixed(2)} KB</small>
                            </div>
                        </div>
                    </div>
                `;
                                };
                                reader.readAsDataURL(file);
                            }
                        });
                    </script>
                @endpush
            </div>
        </div>
    </div>
    </div>
@endsection
