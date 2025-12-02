<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Untuk mahasiswa/laboran
            $table->string('nim')->nullable()->unique();
            $table->string('jurusan')->nullable();
            $table->year('angkatan')->nullable();
            $table->string('no_telepon')->nullable();
            $table->text('alamat')->nullable();
            
            // Role management
            $table->enum('role', ['admin', 'laboran', 'mahasiswa'])->default('mahasiswa');
            
            // Foto profil
            $table->string('foto_profil')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nim', 'jurusan', 'angkatan', 'no_telepon', 'alamat', 'role', 'foto_profil']);
        });
    }
};