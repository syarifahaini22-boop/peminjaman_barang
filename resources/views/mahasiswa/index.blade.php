@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
    <div class="container-fluid">
        <!-- Header dengan tombol tambah -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>

            </div>
            <a href="{{ route('peminjaman.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Mahasiswa
            </a>
        </div>



        <!-- Tambahkan di bagian bawah view -->
        <div id="icon-test" style="display: none;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye"
                viewBox="0 0 16 16">
                <path
                    d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
            </svg>
        </div>

        <script>
            // Jika icon tidak muncul, ganti dengan SVG
            document.addEventListener('DOMContentLoaded', function() {
                const icons = document.querySelectorAll('i.fas, i.bi');
                icons.forEach(icon => {
                    // Cek apakah icon terlihat
                    const computedStyle = window.getComputedStyle(icon, ':before');
                    if (computedStyle.content === 'none' || computedStyle.content === '""') {
                        console.log('Icon not loaded:', icon.className);
                        // Tambahkan text fallback
                        const parent = icon.parentElement;
                        if (parent.classList.contains('btn')) {
                            const iconName = icon.className.includes('eye') ? '👁️' :
                                icon.className.includes('edit') ? '✏️' :
                                icon.className.includes('trash') ? '🗑️' : '🔗';
                            parent.innerHTML = iconName + parent.innerHTML;
                        }
                    }
                });
            });
        </script>

        <!-- Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('mahasiswa.index') }}">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <input type="text" name="keyword" class="form-control"
                                placeholder="Cari nama, NIM, atau jurusan..." value="{{ $keyword }}">
                        </div>
                        <div class="col-md-4">
                            <div class="d-grid gap-2 d-md-flex">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Cari
                                </button>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Mahasiswa -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIM</th>
                                <th>Nama</th>

                                <th>Fakultas</th>
                                <th>Jurusan</th>
                                <th>No. HP</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mahasiswa as $item)
                                <tr>
                                    <td>{{ ($mahasiswa->currentPage() - 1) * $mahasiswa->perPage() + $loop->iteration }}
                                    </td>
                                    <td><strong>{{ $item->nim }}</strong></td>
                                    <td>{{ $item->name }}</td>

                                    <td>{{ $item->fakultas }}</td>
                                    <td>{{ $item->jurusan }}</td>
                                    <td>{{ $item->no_hp ?? '-' }}</td>
                                    <td class="table-actions">


                                        <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Tombol Edit -->
                                        <a href="{{ route('mahasiswa.edit', $item->id) }}" class="btn btn-sm btn-warning"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Tombol Hapus -->
                                        <form action="/mahasiswa/{{ $item->id }}" method="POST"
                                            onsubmit="return confirm('Yakin hapus mahasiswa ini?')"
                                            style="margin: 0; display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-user-slash fa-2x mb-3"></i>
                                            <p>Tidak ada data mahasiswa</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $mahasiswa->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
