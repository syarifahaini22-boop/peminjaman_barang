@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="text-center mb-4">
    <h3 class="mb-3">Buat Akun Baru</h3>
    <p class="text-muted">Daftar untuk mengakses sistem peminjaman barang</p>
</div>

<form method="POST" action="{{ route('register') }}">
    @csrf
    
    <div class="mb-3">
        <label for="name" class="form-label">Nama Lengkap</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" 
               id="name" name="name" value="{{ old('name') }}" required autofocus>
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" 
               id="email" name="email" value="{{ old('email') }}" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control @error('password') is-invalid @enderror" 
               id="password" name="password" required>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    
    <div class="mb-3">
        <label for="password-confirm" class="form-label">Konfirmasi Password</label>
        <input type="password" class="form-control" 
               id="password-confirm" name="password_confirmation" required>
    </div>
    
    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-person-plus me-2"></i> Daftar
        </button>
        
        <a href="{{ route('login') }}" class="btn btn-outline-primary">
            <i class="bi bi-box-arrow-in-right me-2"></i> Sudah Punya Akun? Login
        </a>
    </div>
</form>
@endsection