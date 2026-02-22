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
        Schema::create('kasbon', function (Blueprint $table) {
            $table->id('id_kasbon');
            $table->unsignedBigInteger('id_karyawan');
            $table->string('periode'); // Format: YYYY-MM
            $table->date('tanggal_pengajuan');
            $table->text('deskripsi');
            $table->decimal('nominal', 15, 2);
            $table->enum('metode_pembayaran', ['Langsung', 'Cicilan'])->default('Langsung');
            $table->enum('status_pembayaran', ['Pending', 'Lunas'])->default('Pending');
            $table->integer('jumlah_cicilan')->nullable(); // For Cicilan method
            $table->integer('cicilan_terbayar')->default(0); // For Cicilan method
            $table->decimal('sisa_cicilan', 15, 2)->default(0); // Auto-calculated
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->foreign('id_karyawan')->references('id_karyawan')->on('karyawan')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kasbon');
    }
};
