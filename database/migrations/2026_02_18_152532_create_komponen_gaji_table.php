<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komponen_gaji', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Gaji Pokok, BPJS Kesehatan, Tunjangan Prestasi, dll
            $table->string('kode')->unique(); // gaji_pokok, bpjs_kesehatan
            $table->enum('tipe', ['pendapatan', 'pengeluaran']);
            $table->string('kategori'); // gaji, bpjs, tunjangan, benefit, potongan
            $table->text('deskripsi')->nullable();
            $table->boolean('is_system')->default(false); // System component cannot be deleted
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komponen_gaji');
    }
};
