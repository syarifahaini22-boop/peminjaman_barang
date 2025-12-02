<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Buat admin
        User::create([
            'name' => 'Admin Lab RSI',
            'email' => 'admin@labrsi.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Buat laboran
        User::create([
            'name' => 'Laboran RSI',
            'email' => 'laboran@labrsi.com',
            'password' => Hash::make('password123'),
            'role' => 'laboran',
            'email_verified_at' => now(),
        ]);

        // Buat 10 mahasiswa
        User::factory(10)->create([
            'role' => 'mahasiswa',
            'jurusan' => 'Teknik Informatika',
            'angkatan' => 2022,
        ]);

        // Buat 30 barang
        Barang::factory(30)->create();

        // Buat 15 peminjaman
        Peminjaman::factory(15)->create();
    }
}