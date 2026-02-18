<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nki', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan', 'id_karyawan')->onDelete('cascade');
            $table->string('periode'); // YYYY-MM
            
            // NKI Components (20% each = 100%)
            $table->decimal('kemampuan', 5, 2)->default(0); // 0-10
            $table->decimal('konstribusi', 5, 2)->default(0); // 0-10
            $table->decimal('kedisiplinan', 5, 2)->default(0); // 0-10
            $table->decimal('lainnya', 5, 2)->default(0); // 0-10
            
            // Calculated NKI (weighted average)
            $table->decimal('nilai_nki', 5, 2)->default(0); // 0-10
            
            // Persentase Tunjangan Prestasi
            $table->integer('persentase_prestasi')->default(0); // 70%, 80%, 100%
            
            $table->text('catatan')->nullable();
            $table->foreignId('dinilai_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->unique(['karyawan_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nki');
    }
};
