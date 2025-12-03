@extends('layouts.app')

@section('title', 'Data Mahasiswa')

@section('content')
<div class="container-fluid">
    <!-- Header dengan tombol tambah -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">
            <i class="fas fa-users"></i> Data Mahasiswa
        </h1>
        <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Tambah Mahasiswa
        </a>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('mahasiswa.index') }}">
                <div class="row g-3">
                    <div class="col-md-8">
                        <input type="text" name="keyword" class="form-control" 
                               placeholder="Cari nama, NIM, email, atau jurusan..." 
                               value="{{ request('keyword') }}">
                    </div>
                    <div class="col-md-4">
                        <div class="d-grid gap-2 d-md-flex">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Cari
                            </button>
                            <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset
                            </a>
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
                            <th>Email</th>
                            <th>Fakultas</th>
                            <th>Jurusan</th>
                            <th>No. HP</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $mahasiswa = App\Models\User::where('role', 'mahasiswa')->paginate(10);
                        @endphp
                        
                        @forelse($mahasiswa as $item)
                            <tr>
                                <td>{{ ($mahasiswa->currentPage() - 1) * $mahasiswa->perPage() + $loop->iteration }}</td>
                                <td><strong>{{ $item->nim }}</strong></td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>{{ $item->fakultas }}</td>
                                <td>{{ $item->jurusan }}</td>
                                <td>{{ $item->no_hp ?? '-' }}</td>
                                <td class="table-actions">
                                    <a href="{{ route('mahasiswa.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('mahasiswa.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('mahasiswa.destroy', $item->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus" 
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus mahasiswa ini?')">
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