<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

     // Tambahkan ini di dalam class Mahasiswa
    protected $table = 'mahasiswa'; // Menentukan nama tabel secara manual

    protected $fillable = [
        'name',
        'nim',
        'no_hp',
        'fakultas',
        'jurusan',
    ];
}