<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id('id_absensi');
            $table->unsignedBigInteger('id_karyawan');
            $table->string('periode'); // Format: YYYY-MM
            $table->integer('jumlah_hari_bulan')->default(30); // Auto-calculated
            $table->integer('hadir')->default(0);
            $table->integer('on_site')->default(0);
            $table->integer('absence')->default(0);
            $table->integer('idle_rest')->default(0);
            $table->integer('izin_sakit_cuti')->default(0);
            $table->integer('tanpa_keterangan')->default(0);
            $table->decimal('potongan_absensi', 15, 2)->default(0); // Calculated in acuan gaji
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->foreign('id_karyawan')->references('id_karyawan')->on('karyawan')->onDelete('cascade');
            $table->unique(['id_karyawan', 'periode'], 'unique_absensi_periode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
