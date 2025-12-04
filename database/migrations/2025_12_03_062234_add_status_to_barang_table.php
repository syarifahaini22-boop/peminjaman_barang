<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// NAMA CLASS HARUS: AddStatusToBarangTable
class AddStatusToBarangTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('barang', function (Blueprint $table) {
        if (!Schema::hasColumn('barang', 'status')) {
            $table->enum('status', ['tersedia', 'dipinjam', 'rusak', 'maintenance'])
                  ->default('tersedia');
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}