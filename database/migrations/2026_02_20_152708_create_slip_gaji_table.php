<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slip_gaji', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hitung_gaji_id')->constrained('hitung_gaji')->onDelete('cascade');
            $table->foreignId('karyawan_id')->constrained('karyawan', 'id_karyawan')->onDelete('cascade');
            $table->string('periode'); // YYYY-MM
            $table->string('nomor_slip')->unique(); // SG-YYYYMM-XXXX
            
            // Karyawan Info (snapshot)
            $table->string('nama_karyawan');
            $table->string('jabatan');
            $table->string('status_pegawai');
            $table->date('tanggal_mulai_bekerja');
            $table->string('masa_kerja');
            
            // Gaji Details (read-only snapshot)
            $table->json('detail_pendapatan'); // All pendapatan items
            $table->json('detail_pengeluaran'); // All pengeluaran items
            
            $table->decimal('total_pendapatan', 15, 2);
            $table->decimal('total_pengeluaran', 15, 2);
            $table->decimal('take_home_pay', 15, 2);
            
            // Metadata
            $table->timestamp('generated_at');
            $table->foreignId('generated_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            $table->unique(['karyawan_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slip_gaji');
    }
};
