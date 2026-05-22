<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kasbon', function (Blueprint $table) {
            $table->unique(['id_karyawan', 'periode'], 'kasbon_karyawan_periode_unique');
        });

        Schema::table('karyawan', function (Blueprint $table) {
            $table->unique('nama_karyawan', 'karyawan_nama_unique');
        });
    }

    public function down(): void
    {
        Schema::table('kasbon', function (Blueprint $table) {
            $table->dropUnique('kasbon_karyawan_periode_unique');
        });

        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropUnique('karyawan_nama_unique');
        });
    }
};
