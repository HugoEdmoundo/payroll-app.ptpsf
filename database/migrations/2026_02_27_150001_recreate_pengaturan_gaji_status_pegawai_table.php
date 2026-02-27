<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old table
        Schema::dropIfExists('pengaturan_gaji_status_pegawai');
        
        // Create new structure - Only status_pegawai + lokasi + gaji_pokok
        // Berlaku untuk semua jabatan dan jenis karyawan
        Schema::create('pengaturan_gaji_status_pegawai', function (Blueprint $table) {
            $table->id('id_pengaturan');
            $table->enum('status_pegawai', ['Harian', 'OJT']); // Only Harian and OJT
            $table->string('lokasi_kerja');
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            // Unique constraint untuk kombinasi status_pegawai + lokasi_kerja
            $table->unique(['status_pegawai', 'lokasi_kerja'], 'unique_status_lokasi');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_gaji_status_pegawai');
    }
};
