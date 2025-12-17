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



    // Relasi peminjaman yang masih aktif
    public function peminjamanAktif()
    {
        return $this->peminjaman()->where('status', 'dipinjam');
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

    public function peminjaman()
    {
        return $this->belongsToMany(Peminjaman::class, 'peminjaman_barang')
            ->withPivot('jumlah')
            ->withTimestamps();
    }

    // Hitung stok tersedia
    public function getStokTersediaAttribute()
    {
        $dipinjam = $this->peminjamanAktif()->sum('peminjaman_barang.jumlah');
        return $this->stok - $dipinjam;
    }
}
