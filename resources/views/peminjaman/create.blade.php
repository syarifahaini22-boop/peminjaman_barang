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
                                <select class="form-select select2" id="barang_id" name="barang_id" required>
                                    <option value="">Pilih Barang</option>
                                    @foreach($barang as $item)
                                        <option value="{{ $item->id }}" data-stok="{{ $item->stok_tersedia }}">
                                            {{ $item->nama }} (Stok: {{ $item->stok_tersedia }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('barang_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="mahasiswa_id" class="form-label">Mahasiswa *</label>
                                <select class="form-select select2" id="mahasiswa_id" name="mahasiswa_id" required>
                                    <option value="">Pilih Mahasiswa</option>
                                    @foreach($mahasiswa as $mhs)
                                        <option value="{{ $mhs->id }}">{{ $mhs->name }} - {{ $mhs->email }}</option>
                                    @endforeach
                                </select>
                                @error('mahasiswa_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="jumlah" class="form-label">Jumlah *</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" 
                                       min="1" value="{{ old('jumlah', 1) }}" required>
                                <small class="text-muted" id="stok-info">Stok tersedia: -</small>
                                @error('jumlah')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam *</label>
                                <input type="date" class="form-control" id="tanggal_pinjam" 
                                       name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}" required>
                                @error('tanggal_pinjam')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_kembali" class="form-label">Tanggal Kembali *</label>
                                <input type="date" class="form-control" id="tanggal_kembali" 
                                       name="tanggal_kembali" value="{{ old('tanggal_kembali', date('Y-m-d', strtotime('+7 days'))) }}" required>
                                @error('tanggal_kembali')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" 
                                          rows="3">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="text-danger">{{ $message }}</div>
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
            $('#stok-info').text('Stok tersedia: ' + stok);
            $('#jumlah').attr('max', stok);
        });

        // Set min tanggal untuk tanggal kembali
        $('#tanggal_pinjam').change(function() {
            $('#tanggal_kembali').attr('min', $(this).val());
        });

        // Inisialisasi nilai awal
        $('#barang_id').trigger('change');
    });
</script>
@endpush