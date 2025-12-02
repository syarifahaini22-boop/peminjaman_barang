<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nim',
        'jurusan',
        'angkatan',
        'no_telepon',
        'alamat',
        'role',
        'foto_profil'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'angkatan' => 'integer'
    ];

    // Relasi dengan peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
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