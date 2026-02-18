<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acuan_gaji', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan', 'id_karyawan')->onDelete('cascade');
            $table->foreignId('pengaturan_gaji_id')->nullable()->constrained('pengaturan_gaji')->onDelete('set null');
            $table->string('periode'); // YYYY-MM
            
            // Pendapatan
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->decimal('tunjangan_prestasi', 15, 2)->default(0);
            $table->decimal('tunjangan_konjungtur', 15, 2)->default(0);
            $table->decimal('benefit_ibadah', 15, 2)->default(0);
            $table->decimal('benefit_komunikasi', 15, 2)->default(0);
            $table->decimal('benefit_operasional', 15, 2)->default(0);
            $table->decimal('reward', 15, 2)->default(0);
            
            // BPJS (Pendapatan)
            $table->decimal('bpjs_kesehatan', 15, 2)->default(0);
            $table->decimal('bpjs_kecelakaan_kerja', 15, 2)->default(0);
            $table->decimal('bpjs_kematian', 15, 2)->default(0);
            $table->decimal('bpjs_jht', 15, 2)->default(0);
            $table->decimal('bpjs_jp', 15, 2)->default(0);
            
            // Pengeluaran
            $table->decimal('potongan_bpjs_kesehatan', 15, 2)->default(0);
            $table->decimal('potongan_bpjs_kecelakaan', 15, 2)->default(0);
            $table->decimal('potongan_bpjs_kematian', 15, 2)->default(0);
            $table->decimal('potongan_bpjs_jht', 15, 2)->default(0);
            $table->decimal('potongan_bpjs_jp', 15, 2)->default(0);
            $table->decimal('potongan_koperasi', 15, 2)->default(0);
            $table->decimal('potongan_tabungan_koperasi', 15, 2)->default(0);
            $table->decimal('potongan_kasbon', 15, 2)->default(0);
            $table->decimal('potongan_umroh', 15, 2)->default(0);
            $table->decimal('potongan_kurban', 15, 2)->default(0);
            $table->decimal('potongan_mutabaah', 15, 2)->default(0);
            $table->decimal('potongan_absensi', 15, 2)->default(0);
            $table->decimal('potongan_kehadiran', 15, 2)->default(0);
            
            // Totals
            $table->decimal('total_pendapatan', 15, 2)->default(0);
            $table->decimal('total_pengeluaran', 15, 2)->default(0);
            $table->decimal('take_home_pay', 15, 2)->default(0);
            
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            $table->unique(['karyawan_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acuan_gaji');
    }
};
