@extends('layouts.app')

@section('title', 'Edit Data Peminjaman')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit"></i> Edit Data Peminjaman
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
                                <small class="text-muted">Kode tidak dapat diubah</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" {{ $peminjaman->status == 'dikembalikan' ? 'disabled' : '' }}>
                                    <option value="dipinjam" {{ $peminjaman->status == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                    <option value="dikembalikan" {{ $peminjaman->status == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                                    <option value="terlambat" {{ $peminjaman->status == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                </select>
                                @if($peminjaman->status == 'dikembalikan')
                                    <small class="text-muted">Status tidak dapat diubah setelah dikembalikan</small>
                                @endif
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
                                <label for="tanggal_pinjam" class="form-label">Tanggal Pinjam *</label>
                                <input type="date" class="form-control" id="tanggal_pinjam" 
                                       name="tanggal_pinjam" value="{{ $peminjaman->tanggal_pinjam->format('Y-m-d') }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_kembali" class="form-label">Tanggal Kembali *</label>
                                <input type="date" class="form-control" id="tanggal_kembali" 
                                       name="tanggal_kembali" value="{{ $peminjaman->tanggal_kembali->format('Y-m-d') }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="jumlah" class="form-label">Jumlah *</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" 
                                       min="1" value="{{ $peminjaman->jumlah }}" required>
                                <small class="text-muted" id="stok-info">Stok tersedia: -</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_dikembalikan" class="form-label">Tanggal Dikembalikan</label>
                                <input type="date" class="form-control" id="tanggal_dikembalikan" 
                                       name="tanggal_dikembalikan" 
                                       value="{{ $peminjaman->tanggal_dikembalikan ? $peminjaman->tanggal_dikembalikan->format('Y-m-d') : '' }}">
                                <small class="text-muted">Kosongkan jika belum dikembalikan</small>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ $peminjaman->keterangan }}</textarea>
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
                                @if($peminjaman->status == 'dipinjam')
                                    <a href="{{ route('peminjaman.kembalikan', $peminjaman->id) }}" 
                                       class="btn btn-success"
                                       onclick="return confirm('Tandai sebagai sudah dikembalikan?')">
                                        <i class="fas fa-check"></i> Tandai Dikembalikan
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Info Card -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> Informasi Peminjaman
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Dibuat:</strong> {{ $peminjaman->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Diupdate:</strong> {{ $peminjaman->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            @if($peminjaman->status == 'dipinjam')
                                @if($peminjaman->is_terlambat)
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Peringatan!</strong> Peminjaman sudah melewati batas waktu.
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-clock"></i>
                                        Batas waktu pengembalian: {{ $peminjaman->tanggal_kembali->format('d/m/Y') }}
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
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
    
    // Set min tanggal untuk tanggal kembali
    $('#tanggal_pinjam').change(function() {
        $('#tanggal_kembali').attr('min', $(this).val());
    });
});
</script>
@endpush