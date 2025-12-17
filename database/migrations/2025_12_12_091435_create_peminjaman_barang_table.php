<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek dulu apakah tabel sudah ada
        if (!Schema::hasTable('peminjaman_barang')) {
            Schema::create('peminjaman_barang', function (Blueprint $table) {
                $table->id();
                $table->foreignId('peminjaman_id')->constrained('peminjaman')->onDelete('cascade');
                $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade'); // Pastikan 'barang' bukan 'barangs'
                $table->integer('jumlah')->default(1);
                $table->timestamps();

                // Unique constraint
                $table->unique(['peminjaman_id', 'barang_id']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_barang');
    }
};
