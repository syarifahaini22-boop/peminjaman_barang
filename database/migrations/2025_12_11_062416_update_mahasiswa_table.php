<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            // Tambahkan kolom jika belum ada
            if (!Schema::hasColumn('mahasiswa', 'nim')) {
                $table->string('nim')->unique()->after('id');
            }
            if (!Schema::hasColumn('mahasiswa', 'no_hp')) {
                $table->string('no_hp')->after('name');
            }
            if (!Schema::hasColumn('mahasiswa', 'fakultas')) {
                $table->string('fakultas')->nullable()->after('no_hp');
            }
            if (!Schema::hasColumn('mahasiswa', 'jurusan')) {
                $table->string('jurusan')->nullable()->after('fakultas');
            }

            // Ubah kolom jika sudah ada
            // $table->string('nim')->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('mahasiswa', function (Blueprint $table) {
            // Rollback perubahan
            $table->dropColumn(['nim', 'no_hp', 'fakultas', 'jurusan']);
        });
    }
};
