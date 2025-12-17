<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Cek dulu apakah kolom barang_id ada
        if (Schema::hasColumn('peminjaman', 'barang_id')) {
            Schema::table('peminjaman', function (Blueprint $table) {
                // Hapus foreign key constraint dulu
                $table->dropForeign(['barang_id']);
                // Hapus kolom
                $table->dropColumn('barang_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Tambahkan kembali kolom jika rollback
            $table->foreignId('barang_id')->nullable()->constrained('barang');
        });
    }
};
