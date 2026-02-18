<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_gaji', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_karyawan'); // Konsultan, Organik, Teknisi, Borongan
            $table->string('jabatan');
            $table->foreignId('wilayah_id')->nullable()->constrained('master_wilayah')->onDelete('set null');
            
            // Gaji Pokok & Tunjangan
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->decimal('tunjangan_operasional', 15, 2)->default(0);
            $table->decimal('tunjangan_prestasi', 15, 2)->default(0);
            $table->decimal('tunjangan_konjungtur', 15, 2)->default(0);
            
            // Benefit
            $table->decimal('benefit_ibadah', 15, 2)->default(0);
            $table->decimal('benefit_komunikasi', 15, 2)->default(0);
            $table->decimal('benefit_operasional', 15, 2)->default(0);
            
            // BPJS
            $table->decimal('bpjs_kesehatan', 15, 2)->default(0);
            $table->decimal('bpjs_kecelakaan_kerja', 15, 2)->default(0);
            $table->decimal('bpjs_kematian', 15, 2)->default(0);
            $table->decimal('bpjs_jht', 15, 2)->default(0);
            $table->decimal('bpjs_jp', 15, 2)->default(0);
            
            // Potongan Default
            $table->decimal('potongan_koperasi', 15, 2)->default(100000);
            
            // Calculated Fields
            $table->decimal('net_gaji', 15, 2)->default(0); // Gaji + Tunjangan
            $table->decimal('total_bpjs', 15, 2)->default(0); // Sum BPJS
            $table->decimal('nett', 15, 2)->default(0); // Net Gaji + Total BPJS
            
            $table->boolean('is_active')->default(true);
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            $table->unique(['jenis_karyawan', 'jabatan', 'wilayah_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_gaji');
    }
};
