<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddStokToBarangSeeder extends Seeder
{
    public function run(): void
    {
        // Cek apakah kolom stok sudah ada
        $columns = DB::select('SHOW COLUMNS FROM barang');
        $hasStokColumn = false;
        
        foreach ($columns as $column) {
            if ($column->Field == 'stok') {
                $hasStokColumn = true;
                break;
            }
        }
        
        // Jika tidak ada, tambahkan kolom
        if (!$hasStokColumn) {
            DB::statement('ALTER TABLE barang ADD COLUMN stok INT DEFAULT 0 AFTER kategori');
            $this->command->info('Kolom stok berhasil ditambahkan ke tabel barang.');
        }
        
        // Update semua barang dengan stok random
        Barang::all()->each(function($barang) {
            $barang->update(['stok' => rand(5, 20)]);
        });
        
        $this->command->info('Stok barang berhasil diupdate.');
    }
}