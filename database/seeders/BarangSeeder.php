<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $barang = [
            [
                'kode_barang' => 'BRG001',
                'nama' => 'Laptop Dell XPS',
                'kategori' => 'Elektronik',
                'stok' => 10,
                'lokasi' => 'Ruang IT',
                'deskripsi' => 'Laptop untuk praktikum pemrograman',
            ],
            [
                'kode_barang' => 'BRG002',
                'nama' => 'Proyektor Epson',
                'kategori' => 'Elektronik',
                'stok' => 5,
                'lokasi' => 'Ruang Multimedia',
                'deskripsi' => 'Proyektor untuk presentasi',
            ],
            [
                'kode_barang' => 'BRG003',
                'nama' => 'Buku Algoritma',
                'kategori' => 'Buku',
                'stok' => 20,
                'lokasi' => 'Rak Buku A',
                'deskripsi' => 'Buku referensi algoritma pemrograman',
            ],
            [
                'kode_barang' => 'BRG004',
                'nama' => 'Mikroskop Digital',
                'kategori' => 'Laboratorium',
                'stok' => 3,
                'lokasi' => 'Lab Biologi',
                'deskripsi' => 'Mikroskop untuk praktikum biologi',
            ],
            [
                'kode_barang' => 'BRG005',
                'nama' => 'Router WiFi',
                'kategori' => 'Jaringan',
                'stok' => 8,
                'lokasi' => 'Ruang Server',
                'deskripsi' => 'Router untuk akses internet',
            ],
        ];

        foreach ($barang as $item) {
            Barang::create($item);
        }
    }
}