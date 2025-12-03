<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin Lab RSI',
            'email' => 'admin@labrsi.com',
            'password' => Hash::make('admin'),
            'email_verified_at' => now(),
        ]);

        $mahasiswaData = [
            [
                'name' => 'Mahasiswa Contoh',
                'email' => 'mahasiswa@example.com',
                'password' => bcrypt('password'),
                'role' => 'mahasiswa',
                'nim' => '20230001',
                'fakultas' => 'Teknik',
                'jurusan' => 'Informatika',
                'no_hp' => '081234567890'
            ]
            ];
    }
}