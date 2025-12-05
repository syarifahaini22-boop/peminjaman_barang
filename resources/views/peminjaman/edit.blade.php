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

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Kode Peminjaman</label>
                                    <input type="text" class="form-control" value="{{ $peminjaman->kode_peminjaman }}"
                                        readonly>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-control bg-light">
                                        @if ($peminjaman->status == 'dipinjam')
                                            <span class="badge bg-warning">Dipinjam</span>
                                        @elseif($peminjaman->status == 'dikembalikan')
                                            <span class="badge bg-success">Dikembalikan</span>
                                        @elseif($peminjaman->status == 'terlambat')
                                            <span class="badge bg-danger">Terlambat</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($peminjaman->status) }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="barang_id" class="form-label">Barang *</label>
                                    <select class="form-select select2 @error('barang_id') is-invalid @enderror"
                                        id="barang_id" name="barang_id" required>
                                        <option value="">Pilih Barang</option>
                                        @foreach ($barang as $item)
                                            <option value="{{ $item->id }}" data-stok="{{ $item->stok }}"
                                                {{ old('barang_id', $peminjaman->barang_id) == $item->id ? 'selected' : '' }}>
                                                {{ $item->nama }} (Kode: {{ $item->kode_barang }}) - Stok:
                                                {{ $item->stok }}
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
                                                {{ old('mahasiswa_id', $peminjaman->user_id) == $mhs->id ? 'selected' : '' }}>
                                                {{ $mhs->name }} (NIM: {{ $mhs->nim }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mahasiswa_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Note: user_id di tabel peminjaman akan diisi dengan id
                                        mahasiswa</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_peminjaman" class="form-label">Tanggal Peminjaman *</label>
                                    <input type="date"
                                        class="form-control @error('tanggal_peminjaman') is-invalid @enderror"
                                        id="tanggal_peminjaman" name="tanggal_peminjaman"
                                        value="{{ old('tanggal_peminjaman', $peminjaman->tanggal_peminjaman->format('Y-m-d')) }}"
                                        required>
                                    @error('tanggal_peminjaman')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="tanggal_pengembalian" class="form-label">Tanggal Pengembalian *</label>
                                    <input type="date"
                                        class="form-control @error('tanggal_pengembalian') is-invalid @enderror"
                                        id="tanggal_pengembalian" name="tanggal_pengembalian"
                                        value="{{ old('tanggal_pengembalian', $peminjaman->tanggal_pengembalian->format('Y-m-d')) }}"
                                        required min="{{ $peminjaman->tanggal_peminjaman->format('Y-m-d') }}">
                                    @error('tanggal_pengembalian')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="keterangan" class="form-label">Keterangan / Catatan</label>
                                    <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
                                        rows="3" placeholder="Masukkan keterangan peminjaman...">{{ old('keterangan', $peminjaman->catatan) }}</textarea>
                                    @error('keterangan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Tampilkan informasi tambahan -->
                                @if ($peminjaman->tujuan_peminjaman)
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tujuan Peminjaman</label>
                                        <input type="text" class="form-control"
                                            value="{{ $peminjaman->tujuan_peminjaman }}" readonly>
                                    </div>
                                @endif

                                @if ($peminjaman->lokasi_penggunaan)
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Lokasi Penggunaan</label>
                                        <input type="text" class="form-control"
                                            value="{{ $peminjaman->lokasi_penggunaan }}" readonly>
                                    </div>
                                @endif

                                @if ($peminjaman->dosen_pengampu)
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Dosen Pengampu</label>
                                        <input type="text" class="form-control"
                                            value="{{ $peminjaman->dosen_pengampu }}" readonly>
                                    </div>
                                @endif

                                <!-- Data pengembalian jika sudah dikembalikan -->
                                @if ($peminjaman->status == 'dikembalikan' || $peminjaman->status == 'terlambat')
                                    <div class="col-12 mt-3">
                                        <div class="alert alert-info">
                                            <h6><i class="fas fa-info-circle"></i> Data Pengembalian</h6>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <strong>Tanggal Dikembalikan:</strong><br>
                                                    {{ $peminjaman->tanggal_dikembalikan ? $peminjaman->tanggal_dikembalikan->format('d-m-Y') : '-' }}
                                                </div>
                                                <div class="col-md-4">
                                                    <strong>Kondisi Kembali:</strong><br>
                                                    {{ $peminjaman->kondisi_kembali ? ucfirst($peminjaman->kondisi_kembali) : '-' }}
                                                </div>
                                                @if ($peminjaman->catatan_kembali)
                                                    <div class="col-md-4">
                                                        <strong>Catatan Kembali:</strong><br>
                                                        {{ $peminjaman->catatan_kembali }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-12">
                                    <div class="d-flex justify-content-between mt-4">
                                        <a href="{{ route('peminjaman.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                                        </a>
                                        <div>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save"></i> Update Peminjaman
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- JavaScript untuk validasi tanggal -->
                    @push('scripts')
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Set min date untuk tanggal pengembalian
                                const tanggalPeminjaman = document.getElementById('tanggal_peminjaman');
                                const tanggalPengembalian = document.getElementById('tanggal_pengembalian');

                                if (tanggalPeminjaman && tanggalPengembalian) {
                                    tanggalPeminjaman.addEventListener('change', function() {
                                        tanggalPengembalian.min = this.value;

                                        // Jika tanggal pengembalian lebih awal, reset ke min date
                                        if (tanggalPengembalian.value < this.value) {
                                            tanggalPengembalian.value = this.value;
                                        }
                                    });
                                }

                                // Tampilkan stok berdasarkan barang yang dipilih
                                const barangSelect = document.getElementById('barang_id');
                                const stokInfo = document.getElementById('stok-info');

                                if (barangSelect && stokInfo) {
                                    barangSelect.addEventListener('change', function() {
                                        const selectedOption = this.options[this.selectedIndex];
                                        const stok = selectedOption.getAttribute('data-stok');
                                        stokInfo.textContent = 'Stok tersedia: ' + stok;
                                    });

                                    // Trigger change untuk inisialisasi
                                    barangSelect.dispatchEvent(new Event('change'));
                                }
                            });
                        </script>
                    @endpush
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
