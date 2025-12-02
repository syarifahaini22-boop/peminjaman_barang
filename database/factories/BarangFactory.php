<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BarangFactory extends Factory
{
    public function definition()
    {
        $kategori = ['elektronik', 'alat_lab', 'buku', 'perlengkapan'][rand(0, 3)];
        
        return [
            'kode_barang' => strtoupper(substr($kategori, 0, 3)) . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'nama_barang' => $this->generateNamaBarang($kategori),
            'kategori' => $kategori,
            'deskripsi' => $this->faker->sentence(10),
            'merek' => $this->faker->company(),
            'status' => ['tersedia', 'dipinjam', 'rusak'][rand(0, 2)],
            'lokasi' => 'Rak ' . chr(rand(65, 70)) . rand(1, 10),
            'kondisi' => ['baik', 'rusak_ringan', 'rusak_berat'][rand(0, 2)],
            'tahun_pengadaan' => $this->faker->numberBetween(2018, 2024),
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
        ];
    }

    private function generateNamaBarang($kategori)
    {
        $items = [
            'elektronik' => ['Laptop ASUS X441U', 'Monitor Dell 24"', 'Keyboard Mechanical', 'Mouse Wireless', 'Speaker Bluetooth', 'Tablet Samsung', 'Projector Epson'],
            'alat_lab' => ['Multimeter Digital', 'Oscilloscope', 'Power Supply', 'Function Generator', 'Soldering Iron', 'Toolkit Elektronik', 'Breadboard'],
            'buku' => ['Jaringan Komputer', 'Pemrograman Web', 'Database System', 'Algoritma Pemrograman', 'Sistem Operasi', 'Elektronika Dasar', 'Mikrokontroller'],
            'perlengkapan' => ['Kursi Laboratorium', 'Meja Kerja', 'Lemari Penyimpanan', 'Rak Buku', 'Whiteboard', 'AC Split', 'Lampu LED']
        ];

        return $items[$kategori][array_rand($items[$kategori])];
    }
}