<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kasbon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan', 'id_karyawan')->onDelete('cascade');
            $table->string('nomor_kasbon')->unique(); // KB-YYYYMMDD-XXXX
            $table->date('tanggal_pengajuan');
            
            $table->text('deskripsi');
            $table->decimal('nominal', 15, 2);
            
            // Payment Method
            $table->enum('metode_pembayaran', ['langsung', 'cicilan'])->default('langsung');
            $table->integer('jumlah_cicilan')->nullable(); // Jika cicilan
            $table->decimal('nominal_cicilan', 15, 2)->nullable();
            
            // Status
            $table->enum('status', ['pending', 'approved', 'rejected', 'lunas'])->default('pending');
            $table->decimal('sisa_hutang', 15, 2)->default(0);
            
            // Approval
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('catatan_approval')->nullable();
            
            $table->timestamps();
        });
        
        // Kasbon Cicilan (Payment History)
        Schema::create('kasbon_cicilan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kasbon_id')->constrained('kasbon')->onDelete('cascade');
            $table->string('periode'); // YYYY-MM (periode potong gaji)
            $table->decimal('nominal', 15, 2);
            $table->date('tanggal_bayar');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kasbon_cicilan');
        Schema::dropIfExists('kasbon');
    }
};
