<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // ✔ BENAR
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'nim',
        'fakultas',
        'jurusan',
        'no_hp',
        'password',
        'role',
    ];

    // Relasi dengan peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'user_id');
    }

    // Peminjaman aktif user
    public function peminjamanAktif()
    {
        return $this->hasMany(Peminjaman::class)->where('status', 'dipinjam');
    }

    // Check jika user adalah admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isLaboran()
    {
        return $this->role === 'laboran';
    }

    public function isMahasiswa()
    {
        return $this->role === 'mahasiswa';
    }

    // Accessor untuk role badge
    public function getRoleBadgeAttribute()
    {
        $roles = [
            'admin' => 'danger',
            'laboran' => 'warning',
            'mahasiswa' => 'success'
        ];

        return $roles[$this->role] ?? 'secondary';
    }
}