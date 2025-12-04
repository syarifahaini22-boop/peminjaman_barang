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
                            
                            <!-- HAPUS FIELD JUMLAH -->
                            {{-- 
                            <div class="col-md-6 mb-3">
                                <label for="jumlah" class="form-label">Jumlah *</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" 
                                       min="1" value="{{ old('jumlah', $peminjaman->jumlah) }}" required>
                                <small class="text-muted" id="stok-info">Stok tersedia: -</small>
                            </div>
                            --}}
                            
                            <!-- GANTI: tanggal_pinjam → tanggal_peminjaman -->
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_peminjaman" class="form-label">Tanggal Peminjaman *</label>
                                <input type="date" class="form-control" id="tanggal_peminjaman" 
                                       name="tanggal_peminjaman" 
                                       value="{{ old('tanggal_peminjaman', $peminjaman->tanggal_peminjaman->format('Y-m-d')) }}" required>
                            </div>
                            
                            <!-- GANTI: tanggal_kembali → tanggal_pengembalian -->
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_pengembalian" class="form-label">Tanggal Pengembalian *</label>
                                <input type="date" class="form-control" id="tanggal_pengembalian" 
                                       name="tanggal_pengembalian" 
                                       value="{{ old('tanggal_pengembalian', $peminjaman->tanggal_pengembalian->format('Y-m-d')) }}" required>
                            </div>
                            
                            <!-- TAMBAHKAN: tujuan_peminjaman -->
                            <div class="col-md-12 mb-3">
                                <label for="tujuan_peminjaman" class="form-label">Tujuan Peminjaman *</label>
                                <textarea class="form-control" id="tujuan_peminjaman" name="tujuan_peminjaman" 
                                          rows="2" required>{{ old('tujuan_peminjaman', $peminjaman->tujuan_peminjaman) }}</textarea>
                            </div>
                            
                            <!-- TAMBAHKAN: lokasi_penggunaan -->
                            <div class="col-md-6 mb-3">
                                <label for="lokasi_penggunaan" class="form-label">Lokasi Penggunaan</label>
                                <input type="text" class="form-control" id="lokasi_penggunaan" 
                                       name="lokasi_penggunaan" 
                                       value="{{ old('lokasi_penggunaan', $peminjaman->lokasi_penggunaan) }}">
                            </div>
                            
                            <!-- TAMBAHKAN: dosen_pengampu -->
                            <div class="col-md-6 mb-3">
                                <label for="dosen_pengampu" class="form-label">Dosen Pengampu</label>
                                <input type="text" class="form-control" id="dosen_pengampu" 
                                       name="dosen_pengampu" 
                                       value="{{ old('dosen_pengampu', $peminjaman->dosen_pengampu) }}">
                            </div>
                            
                            <!-- GANTI: keterangan → catatan -->
                            <div class="col-md-12 mb-3">
                                <label for="catatan" class="form-label">Catatan</label>
                                <textarea class="form-control" id="catatan" name="catatan" 
                                          rows="2">{{ old('catatan', $peminjaman->catatan) }}</textarea>
                            </div>
                        </div>
                        
                        <!-- Tampilkan data pengembalian jika sudah dikembalikan -->
                        @if($peminjaman->status == 'dikembalikan' || $peminjaman->status == 'terlambat')
                        <div class="alert alert-info mt-3">
                            <h6><i class="fas fa-info-circle"></i> Data Pengembalian</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Tanggal Dikembalikan:</strong><br>
                                    {{ $peminjaman->tanggal_dikembalikan->format('d-m-Y') }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Kondisi Kembali:</strong><br>
                                    {{ ucfirst($peminjaman->kondisi_kembali) }}
                                </div>
                                @if($peminjaman->catatan_kembali)
                                <div class="col-md-12 mt-2">
                                    <strong>Catatan Kembali:</strong><br>
                                    {{ $peminjaman->catatan_kembali }}
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif
                        
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
            $('#stok-info').text('Stok tersedia: ' + stok);
        }

        $('#barang_id').change(updateStokInfo);
        updateStokInfo();
        
        // Set min tanggal untuk tanggal pengembalian
        $('#tanggal_peminjaman').change(function() {
            $('#tanggal_pengembalian').attr('min', $(this).val());
        });
    });
</script>
@endpush