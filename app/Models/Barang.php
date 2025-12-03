<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Peminjaman;

class Barang extends Model
{
    use HasFactory, SoftDeletes;

    // Tentukan nama tabel secara eksplisit
    protected $table = 'barang';

    protected $fillable = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'stok',
        'deskripsi',
        'merek',
        'status',
        'lokasi',
        'kondisi',
        'tahun_pengadaan',
        'gambar',
        'qr_code'
    ];

    protected $casts = [
        'tahun_pengadaan' => 'integer'
    ];
    
    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'tersedia' => 'success',
            'dipinjam' => 'warning',
            'rusak' => 'danger',
            'maintenance' => 'info'
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



    public function peminjamanAktif()
{
    return $this->hasOne(Peminjaman::class)
                ->where('status', 'dipinjam')
                ->latest(); // ambil yang terbaru
}


// Jika ingin menghitung berapa banyak yang sedang dipinjam
public function getTotalDipinjamAttribute()
{
    // Jika tidak ada kolom jumlah, hitung berdasarkan jumlah record
    return $this->peminjamans()->where('status', 'dipinjam')->count();
    
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
            ->sum('barang_id');

        return $stok - $dipinjam;
    }
}