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
        'user_id', // Bukan mahasiswa_id
        'kode_peminjaman',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'tanggal_dikembalikan',
        'status',
        'tujuan_peminjaman',
        'lokasi_penggunaan',
        'dosen_pengampu',
        'catatan',
        'kondisi_kembali',
        'catatan_kembali',
    ];

    protected $casts = [
        'tanggal_peminjaman' => 'date',
        'tanggal_pengembalian' => 'date',
        'tanggal_dikembalikan' => 'date',
    ];

    // Relasi ke Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
    public function user()
    {
        return $this->belongsTo(Mahasiswa::class, 'user_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'user_id');
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



    // TAMBAHKAN accessor untuk kompatibilitas
    public function getMahasiswaIdAttribute()
    {
        return $this->user_id;
    }

    public function setMahasiswaIdAttribute($value)
    {
        $this->attributes['user_id'] = $value;
    }
}
