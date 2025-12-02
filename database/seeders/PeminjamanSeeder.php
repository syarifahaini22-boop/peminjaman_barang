<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswa = User::where('role', 'mahasiswa')->first();
        $barang = Barang::first();
        
        if (!$mahasiswa || !$barang) {
            return;
        }
        
        // Data peminjaman aktif
        Peminjaman::create([
            'barang_id' => $barang->id,
            'mahasiswa_id' => $mahasiswa->id,
            'kode_peminjaman' => 'PINJ-' . date('Ymd') . '-0001',
            'tanggal_pinjam' => Carbon::now()->subDays(2),
            'tanggal_kembali' => Carbon::now()->addDays(5),
            'status' => 'dipinjam',
            'jumlah' => 2,
            'keterangan' => 'Peminjaman untuk praktikum',
        ]);
        
        // Data peminjaman selesai
        Peminjaman::create([
            'barang_id' => $barang->id,
            'mahasiswa_id' => $mahasiswa->id,
            'kode_peminjaman' => 'PINJ-' . date('Ymd', strtotime('-10 days')) . '-0002',
            'tanggal_pinjam' => Carbon::now()->subDays(10),
            'tanggal_kembali' => Carbon::now()->subDays(3),
            'tanggal_dikembalikan' => Carbon::now()->subDays(2),
            'status' => 'dikembalikan',
            'jumlah' => 1,
            'keterangan' => 'Sudah dikembalikan',
        ]);
        
        // Data peminjaman terlambat
        Peminjaman::create([
            'barang_id' => $barang->id,
            'mahasiswa_id' => $mahasiswa->id,
            'kode_peminjaman' => 'PINJ-' . date('Ymd', strtotime('-15 days')) . '-0003',
            'tanggal_pinjam' => Carbon::now()->subDays(15),
            'tanggal_kembali' => Carbon::now()->subDays(8),
            'tanggal_dikembalikan' => Carbon::now()->subDays(6),
            'status' => 'terlambat',
            'jumlah' => 1,
            'keterangan' => 'Telat mengembalikan',
        ]);
    }
}