<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_gaji', function (Blueprint $table) {
            $table->id('id_pengaturan');
            $table->string('jenis_karyawan'); // Konsultan, Organik, Teknisi, Borongan
            $table->string('jabatan');
            $table->string('lokasi_kerja');
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->decimal('tunjangan_operasional', 15, 2)->default(0);
            $table->decimal('potongan_koperasi', 15, 2)->default(0);
            $table->decimal('gaji_nett', 15, 2)->default(0);
            $table->decimal('bpjs_kesehatan', 15, 2)->default(0);
            $table->decimal('bpjs_ketenagakerjaan', 15, 2)->default(0);
            $table->decimal('bpjs_kecelakaan_kerja', 15, 2)->default(0);
            $table->decimal('bpjs_total', 15, 2)->default(0);
            $table->decimal('total_gaji', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            // Unique constraint untuk kombinasi jenis_karyawan, jabatan, lokasi_kerja
            $table->unique(['jenis_karyawan', 'jabatan', 'lokasi_kerja'], 'unique_pengaturan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_gaji');
    }
};
