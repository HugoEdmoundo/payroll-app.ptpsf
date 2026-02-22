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
        Schema::create('acuan_gaji', function (Blueprint $table) {
            $table->id('id_acuan');
            $table->unsignedBigInteger('id_karyawan');
            $table->string('periode'); // Format: YYYY-MM
            
            // Pendapatan
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->decimal('bpjs_kesehatan_pendapatan', 15, 2)->default(0);
            $table->decimal('bpjs_kecelakaan_kerja_pendapatan', 15, 2)->default(0);
            $table->decimal('bpjs_kematian_pendapatan', 15, 2)->default(0);
            $table->decimal('bpjs_jht_pendapatan', 15, 2)->default(0);
            $table->decimal('bpjs_jp_pendapatan', 15, 2)->default(0);
            $table->decimal('tunjangan_prestasi', 15, 2)->default(0);
            $table->decimal('tunjangan_konjungtur', 15, 2)->default(0);
            $table->decimal('benefit_ibadah', 15, 2)->default(0);
            $table->decimal('benefit_komunikasi', 15, 2)->default(0);
            $table->decimal('benefit_operasional', 15, 2)->default(0);
            $table->decimal('reward', 15, 2)->default(0);
            $table->decimal('total_pendapatan', 15, 2)->default(0);
            
            // Pengeluaran
            $table->decimal('bpjs_kesehatan_pengeluaran', 15, 2)->default(0);
            $table->decimal('bpjs_kecelakaan_kerja_pengeluaran', 15, 2)->default(0);
            $table->decimal('bpjs_kematian_pengeluaran', 15, 2)->default(0);
            $table->decimal('bpjs_jht_pengeluaran', 15, 2)->default(0);
            $table->decimal('bpjs_jp_pengeluaran', 15, 2)->default(0);
            $table->decimal('tabungan_koperasi', 15, 2)->default(0);
            $table->decimal('koperasi', 15, 2)->default(0);
            $table->decimal('kasbon', 15, 2)->default(0);
            $table->decimal('umroh', 15, 2)->default(0);
            $table->decimal('kurban', 15, 2)->default(0);
            $table->decimal('mutabaah', 15, 2)->default(0);
            $table->decimal('potongan_absensi', 15, 2)->default(0);
            $table->decimal('potongan_kehadiran', 15, 2)->default(0);
            $table->decimal('total_pengeluaran', 15, 2)->default(0);
            
            $table->decimal('gaji_bersih', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->foreign('id_karyawan')->references('id_karyawan')->on('karyawan')->onDelete('cascade');
            $table->unique(['id_karyawan', 'periode'], 'unique_acuan_periode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acuan_gaji');
    }
};
