<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_bpjs_koperasi', function (Blueprint $table) {
            $table->id();
            
            // BPJS Components (Pendapatan)
            $table->decimal('bpjs_kesehatan', 15, 2)->default(0);
            $table->decimal('bpjs_ketenagakerjaan', 15, 2)->default(0);
            $table->decimal('bpjs_kecelakaan_kerja', 15, 2)->default(0);
            $table->decimal('bpjs_jht', 15, 2)->default(0);
            $table->decimal('bpjs_jp', 15, 2)->default(0);
            
            // Koperasi (Potongan)
            $table->decimal('koperasi', 15, 2)->default(0);
            
            // Metadata
            $table->string('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_bpjs_koperasi');
    }
};
