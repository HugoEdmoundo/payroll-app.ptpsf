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
        
        // Create new structure - GLOBAL configuration (no status_pegawai)
        Schema::create('pengaturan_bpjs_koperasi', function (Blueprint $table) {
            $table->id();
            
            // BPJS Pendapatan (Auto applied to Kontrak employees)
            $table->decimal('bpjs_kesehatan_pendapatan', 15, 2)->default(0);
            $table->decimal('bpjs_kecelakaan_kerja_pendapatan', 15, 2)->default(0);
            $table->decimal('bpjs_kematian_pendapatan', 15, 2)->default(0);
            $table->decimal('bpjs_jht_pendapatan', 15, 2)->default(0);
            $table->decimal('bpjs_jp_pendapatan', 15, 2)->default(0);
            
            // Koperasi (Auto applied to Kontrak and OJT employees)
            $table->decimal('koperasi', 15, 2)->default(0);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_bpjs_koperasi');
    }
};
