<?php
// database/migrations/2025_12_15_create_peminjaman_barang_pivot_table_fixed.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman_barang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_id')->constrained('peminjaman')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->timestamps();

            $table->unique(['peminjaman_id', 'barang_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman_barang');
    }
};
