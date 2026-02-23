<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hitung_gaji', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('acuan_gaji_id');
            $table->foreignId('karyawan_id')->constrained('karyawan', 'id_karyawan')->onDelete('cascade');
            $table->string('periode'); // YYYY-MM
            
            // Copy from acuan_gaji (editable)
            $table->json('pendapatan_acuan'); // Original pendapatan from acuan
            $table->json('pengeluaran_acuan'); // Original pengeluaran from acuan
            
            // Penyesuaian (Adjustments)
            $table->json('penyesuaian_pendapatan')->nullable(); // [{komponen, nominal, catatan}]
            $table->json('penyesuaian_pengeluaran')->nullable(); // [{komponen, nominal, catatan}]
            
            // Final Calculations
            $table->decimal('total_pendapatan_acuan', 15, 2)->default(0);
            $table->decimal('total_penyesuaian_pendapatan', 15, 2)->default(0);
            $table->decimal('total_pendapatan_akhir', 15, 2)->default(0);
            
            $table->decimal('total_pengeluaran_acuan', 15, 2)->default(0);
            $table->decimal('total_penyesuaian_pengeluaran', 15, 2)->default(0);
            $table->decimal('total_pengeluaran_akhir', 15, 2)->default(0);
            
            $table->decimal('take_home_pay', 15, 2)->default(0);
            
            // Status
            $table->enum('status', ['draft', 'preview', 'approved'])->default('draft');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->text('catatan_umum')->nullable();
            $table->timestamps();
            
            // Foreign key to acuan_gaji with custom primary key
            $table->foreign('acuan_gaji_id')->references('id_acuan')->on('acuan_gaji')->onDelete('cascade');
            
            $table->unique(['karyawan_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hitung_gaji');
    }
};
