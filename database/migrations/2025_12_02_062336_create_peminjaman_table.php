<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambahkan pengecekan ini
        if (!Schema::hasTable('peminjaman')) {
            Schema::create('peminjaman', function (Blueprint $table) {
                $table->id();
                $table->string('kode_peminjaman');
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->foreignId('barang_id')->constrained()->onDelete('cascade');
                $table->date('tanggal_peminjaman');
                $table->date('tanggal_pengembalian');
                $table->date('tanggal_dikembalikan')->nullable();
                $table->enum('status', ['dipinjam', 'dikembalikan', 'terlambat', 'hilang', 'rusak'])->default('dipinjam');
                $table->text('tujuan_peminjaman');
                $table->string('lokasi_penggunaan')->nullable();
                $table->string('dosen_pengampu')->nullable();
                $table->text('catatan')->nullable();
                $table->enum('kondisi_kembali', ['baik', 'rusak_ringan', 'rusak_berat'])->nullable();
                $table->text('catatan_kembali')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};