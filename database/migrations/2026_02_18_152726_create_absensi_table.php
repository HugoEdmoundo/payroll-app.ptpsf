<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan', 'id_karyawan')->onDelete('cascade');
            $table->string('periode'); // YYYY-MM
            
            // Attendance Data
            $table->integer('hadir')->default(0);
            $table->integer('onsite')->default(0);
            $table->integer('absence')->default(0); // Alpha
            $table->integer('idle_rest')->default(0);
            $table->integer('izin_sakit_cuti')->default(0);
            $table->integer('tanpa_keterangan')->default(0);
            
            // Calculated
            $table->integer('total_hari_kerja')->default(0);
            $table->decimal('potongan_absensi', 15, 2)->default(0);
            
            $table->text('catatan')->nullable();
            $table->timestamps();
            
            $table->unique(['karyawan_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
