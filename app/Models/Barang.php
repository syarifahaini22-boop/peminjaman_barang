<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Peminjaman;

class Barang extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nama',  // GANTI 'nama_barang' menjadi 'nama'
        'kondisi',
        'kategori',
        'stok',
        'deskripsi',
        'gambar',
        'merek',
        'status',
        'lokasi',
        'tahun_pengadaan',
        'qr_code'
    ];


    // Tambahkan accessor untuk kompatibilitas
    public function getNamaBarangAttribute()
    {
        return $this->attributes['nama'] ?? $this->nama;
    }

    public function setNamaBarangAttribute($value)
    {
        $this->attributes['nama'] = $value;
    }

    protected $casts = [
        'tahun_pengadaan' => 'integer'
    ];




    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'tersedia' => 'success',
            'tidak tersedia' => 'warning',  // Ganti 'dipinjam' dengan 'tidak tersedia'
            'rusak' => 'danger',
            'dipinjam' => 'info'  // Ganti 'maintenance' dengan 'dipinjam'
        ];

        return $statuses[$this->status] ?? 'secondary';
    }

    // Accessor untuk kondisi badge
    public function getKondisiBadgeAttribute()
    {
        $kondisi = [
            'baik' => 'success',
            'rusak_ringan' => 'warning',
            'rusak_berat' => 'danger'
        ];

        return $kondisi[$this->kondisi] ?? 'secondary';
    }



    // app/Models/Barang.php
    public function peminjamanAktif()
    {
        // Contoh relationship (sesuaikan dengan model Anda)
        return $this->hasOne(Peminjaman::class)->where('status', 'aktif');
    }


    // Jika ingin menghitung berapa banyak yang sedang dipinjam
    public function getTotalDipinjamAttribute()
    {
        // Jika tidak ada kolom jumlah, hitung berdasarkan jumlah record
        return $this->peminjaman()->where('status', 'dipinjam')->count();

        // Atau jika ada kolom quantity/jumlah:
        // return $this->peminjamans()->where('status', 'dipinjam')->sum('quantity');
    }




    protected $appends = ['stok_tersedia'];

    // Relasi ke Peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }

    // Accessor untuk stok tersedia
    public function getStokTersediaAttribute()
    {
        $stok = $this->stok ?? 10; // default 10 jika kolom stok tidak ada
        $dipinjam = $this->peminjaman()
            ->where('status', 'dipinjam')
            ->sum('barang_id'); // <-- SALAH! Ini menjumlahkan ID, bukan jumlah barang dipinjam

        return $stok - $dipinjam;
    }
}
