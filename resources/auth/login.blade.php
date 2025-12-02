@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="text-center mb-4">
    <h3 class="mb-3">Login ke Sistem</h3>
    <p class="text-muted">Masukkan kredensial Anda untuk mengakses sistem</p>
</div>

<form method="POST" action="{{ route('login') }}">
    @csrf
    
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" 
               id="email" name="email" value="{{ old('email') }}" required autofocus>
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
    
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label" for="remember">Ingat saya</label>
    </div>
    
    <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="bi bi-box-arrow-in-right me-2"></i> Login
        </button>
        
        @if (Route::has('register'))
            <a href="{{ route('register') }}" class="btn btn-outline-primary">
                <i class="bi bi-person-plus me-2"></i> Buat Akun Baru
            </a>
        @endif
        
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="btn btn-link">
                Lupa Password?
            </a>
        @endif
    </div>
</form>
@endsection