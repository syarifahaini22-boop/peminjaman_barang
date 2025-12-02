@extends('layouts.app')

@section('title', 'Detail Peminjaman')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> Detail Peminjaman
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Peminjaman</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Kode Peminjaman</th>
                                    <td>{{ $peminjaman->kode_peminjaman }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($peminjaman->status == 'dipinjam')
                                            <span class="badge bg-warning">Dipinjam</span>
                                        @elseif($peminjaman->status == 'dikembalikan')
                                            <span class="badge bg-success">Dikembalikan</span>
                                        @else
                                            <span class="badge bg-danger">Terlambat</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pinjam</th>
                                    <td>{{ $peminjaman->tanggal_pinjam->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Kembali</th>
                                    <td>{{ $peminjaman->tanggal_kembali->format('d/m/Y') }}</td>
                                </tr>
                                @if($peminjaman->tanggal_dikembalikan)
                                <tr>
                                    <th>Tanggal Dikembalikan</th>
                                    <td>{{ $peminjaman->tanggal_dikembalikan->format('d/m/Y') }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Jumlah</th>
                                    <td>{{ $peminjaman->jumlah }}</td>
                                </tr>
                            </table>
                        </div>
                        
                        <div class="col-md-6">
                            <h6>Informasi Barang & Mahasiswa</h6>
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Barang</th>
                                    <td>{{ $peminjaman->barang->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Kode Barang</th>
                                    <td>{{ $peminjaman->barang->kode_barang }}</td>
                                </tr>
                                <tr>
                                    <th>Mahasiswa</th>
                                    <td>{{ $peminjaman->mahasiswa->name }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $peminjaman->mahasiswa->email }}</td>
                                </tr>
                                <tr>
                                    <th>Keterangan</th>
                                    <td>{{ $peminjaman->keterangan ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        @if($peminjaman->status == 'dipinjam')
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> 
                                Barang masih dalam status dipinjam. 
                                @if($peminjaman->is_terlambat)
                                    <strong class="text-danger">Sudah melewati batas waktu pengembalian!</strong>
                                @else
                                    Harap dikembalikan sebelum {{ $peminjaman->tanggal_kembali->format('d/m/Y') }}
                                @endif
                            </div>
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-between mt-3">
                        <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                        </a>
                        
                        @if($peminjaman->status == 'dipinjam')
                            <div class="btn-group">
                                <a href="{{ route('peminjaman.edit', $peminjaman->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('peminjaman.kembalikan', $peminjaman->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Apakah barang sudah dikembalikan?')">
                                        <i class="fas fa-undo"></i> Kembalikan
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection