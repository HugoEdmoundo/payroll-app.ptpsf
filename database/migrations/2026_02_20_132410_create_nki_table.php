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
        Schema::create('nki', function (Blueprint $table) {
            $table->id('id_nki');
            $table->unsignedBigInteger('id_karyawan');
            $table->string('periode'); // Format: YYYY-MM (e.g., 2026-02)
            $table->decimal('kemampuan', 5, 2)->default(0); // Max 100.00
            $table->decimal('kontribusi', 5, 2)->default(0); // Max 100.00
            $table->decimal('kedisiplinan', 5, 2)->default(0); // Max 100.00
            $table->decimal('lainnya', 5, 2)->default(0); // Max 100.00
            $table->decimal('nilai_nki', 5, 2)->default(0); // Auto-calculated
            $table->integer('persentase_tunjangan')->default(0); // 70, 80, or 100
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->foreign('id_karyawan')->references('id_karyawan')->on('karyawan')->onDelete('cascade');
            $table->unique(['id_karyawan', 'periode'], 'unique_nki_periode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nki');
    }
};
