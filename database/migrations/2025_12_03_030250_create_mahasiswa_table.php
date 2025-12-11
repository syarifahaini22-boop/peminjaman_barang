<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // NONAKTIFKAN foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('nim')->unique();
            $table->string('name');
            $table->string('no_hp');
            $table->string('fakultas')->nullable();
            $table->string('jurusan')->nullable();
            $table->timestamps();
        });

        // AKTIFKAN kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('mahasiswa');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
};
