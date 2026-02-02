<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa'; // pastikan ini sesuai dengan tabel Anda
    protected $fillable = [
        'name',
        'nim',
        'no_hp',
        'fakultas',
        'jurusan'
    ];

    // Relasi ke peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'user_id');
    }

    // Accessor untuk total peminjaman (jika diperlukan)
    public function getTotalPeminjamanAttribute()
    {
        return $this->peminjaman()->count();
    }
}
