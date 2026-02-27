<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old table
        Schema::dropIfExists('pengaturan_bpjs_koperasi');
        
        // Create new structure
        Schema::create('pengaturan_bpjs_koperasi', function (Blueprint $table) {
            $table->id();
            $table->enum('status_pegawai', ['Kontrak', 'OJT']); // Only Kontrak and OJT
            
            // BPJS Pendapatan (Only for Kontrak)
            $table->decimal('bpjs_kesehatan', 15, 2)->default(0);
            $table->decimal('bpjs_kecelakaan_kerja', 15, 2)->default(0);
            $table->decimal('bpjs_kematian', 15, 2)->default(0);
            $table->decimal('bpjs_jht', 15, 2)->default(0);
            $table->decimal('bpjs_jp', 15, 2)->default(0);
            
            // Koperasi (For Kontrak and OJT)
            $table->decimal('koperasi', 15, 2)->default(0);
            
            $table->timestamps();
            
            $table->unique('status_pegawai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_bpjs_koperasi');
    }
};
