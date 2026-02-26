<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_gaji_status_pegawai', function (Blueprint $table) {
            $table->id('id_pengaturan');
            $table->string('status_pegawai'); // Harian, OJT, Kontrak
            $table->string('jabatan');
            $table->string('lokasi_kerja');
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            // Unique constraint untuk kombinasi status_pegawai, jabatan, lokasi_kerja
            $table->unique(['status_pegawai', 'jabatan', 'lokasi_kerja'], 'unique_status_pegawai_pengaturan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_gaji_status_pegawai');
    }
};
