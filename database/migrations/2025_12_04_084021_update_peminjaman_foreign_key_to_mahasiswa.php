<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            // Drop foreign key lama
            $table->dropForeign(['user_id']);

            // Tambah foreign key ke mahasiswa
            $table->foreign('user_id')
                ->references('id')
                ->on('mahasiswa')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropForeign(['user_id']);

            // Kembalikan ke users jika rollback
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }
};
