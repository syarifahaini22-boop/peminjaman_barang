@extends('layouts.app')

@section('title', 'Edit Peminjaman')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit"></i> Edit Peminjaman
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('peminjaman.update', $peminjaman->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kode Peminjaman</label>
                                <input type="text" class="form-control" 
                                       value="{{ $peminjaman->kode_peminjaman }}" readonly>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="barang_id" class="form-label">Barang *</label>
                                <select class="form-select select2" id="barang_id" name="barang_id" required>
                                    @foreach($barang as $item)
                                        <option value="{{ $item->id }}" 
                                                data-stok="{{ $item->stok_tersedia }}"
                                                {{ $peminjaman->barang_id == $item->id ? 'selected' : '' }}>
                                            {{ $item->nama }} (Stok: {{ $item->stok_tersedia }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="mahasiswa_id" class="form-label">Mahasiswa *</label>
                                <select class="form-select select2" id="mahasiswa_id" name="mahasiswa_id" required>
                                    @foreach($mahasiswa as $mhs)
                                        <option value="{{ $mhs->id }}"
                                                {{ $peminjaman->mahasiswa_id == $mhs->id ? 'selected' : '' }}>
                                            {{ $mhs->name }} - {{ $mhs->email }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="jumlah" class="form-label">Jumlah *</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" 
                                       min="1" value="{{ old('jumlah', $peminjaman->jumlah) }}" required>
                                <small class="text-muted" id="stok-info">Stok tersedia: -</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam *</label>
                                <input type="date" class="form-control" id="tanggal_pinjam" 
                                       name="tanggal_pinjam" value="{{ old('tanggal_pinjam', $peminjaman->tanggal_pinjam->format('Y-m-d')) }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_kembali" class="form-label">Tanggal Kembali *</label>
                                <input type="date" class="form-control" id="tanggal_kembali" 
                                       name="tanggal_kembali" value="{{ old('tanggal_kembali', $peminjaman->tanggal_kembali->format('Y-m-d')) }}" required>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $peminjaman->keterangan) }}</textarea>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Peminjaman
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
        // Update stok info
        function updateStokInfo() {
            var selected = $('#barang_id').find('option:selected');
            var stok = selected.data('stok') || 0;
            
            // Jika barang sama dengan sebelumnya, tambahkan jumlah yang sedang dipinjam
            var currentBarangId = "{{ $peminjaman->barang_id }}";
            var selectedBarangId = $('#barang_id').val();
            
            if (selectedBarangId == currentBarangId) {
                stok += {{ $peminjaman->jumlah }};
            }
            
            $('#stok-info').text('Stok tersedia: ' + stok);
            $('#jumlah').attr('max', stok);
        }

        $('#barang_id').change(updateStokInfo);
        updateStokInfo();
    });
</script>
@endpush