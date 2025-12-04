<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';

    protected $fillable = [
        'nim',
        'name',
        'no_hp',
        'fakultas',
        'jurusan'
    ];

    // PERBAIKAN: Relasi ke peminjaman melalui users
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class, 'user_id');
    }
}
