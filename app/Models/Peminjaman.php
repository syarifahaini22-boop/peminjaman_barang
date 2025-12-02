<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';
    
    protected $fillable = [
        'barang_id',
        'mahasiswa_id',
        'kode_peminjaman',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_dikembalikan',
        'status',
        'jumlah',
        'keterangan'
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_dikembalikan' => 'date',
    ];

    // Relasi ke Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    // Relasi ke Mahasiswa (User)
    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    // Scope untuk filter status
    public function scopeStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }

    // Cek apakah terlambat
    public function getIsTerlambatAttribute()
    {
        if ($this->status !== 'dikembalikan' && now()->gt($this->tanggal_kembali)) {
            return true;
        }
        return false;
    }
}