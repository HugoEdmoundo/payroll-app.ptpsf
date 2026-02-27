<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop slip_gaji first (has foreign key to hitung_gaji)
        Schema::dropIfExists('slip_gaji');
        
        // Drop old hitung_gaji table
        Schema::dropIfExists('hitung_gaji');
        
        // Create new hitung_gaji structure
        Schema::create('hitung_gaji', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('acuan_gaji_id');
            $table->foreignId('karyawan_id')->constrained('karyawan', 'id_karyawan')->onDelete('cascade');
            $table->string('periode'); // YYYY-MM
            
            // PENDAPATAN - Copy from Acuan Gaji + NKI
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->decimal('bpjs_kesehatan_pendapatan', 15, 2)->default(0);
            $table->decimal('bpjs_kecelakaan_kerja_pendapatan', 15, 2)->default(0);
            $table->decimal('bpjs_kematian_pendapatan', 15, 2)->default(0);
            $table->decimal('bpjs_jht_pendapatan', 15, 2)->default(0);
            $table->decimal('bpjs_jp_pendapatan', 15, 2)->default(0);
            $table->decimal('tunjangan_prestasi', 15, 2)->default(0); // From NKI
            $table->decimal('tunjangan_konjungtur', 15, 2)->default(0);
            $table->decimal('benefit_ibadah', 15, 2)->default(0);
            $table->decimal('benefit_komunikasi', 15, 2)->default(0);
            $table->decimal('benefit_operasional', 15, 2)->default(0);
            $table->decimal('reward', 15, 2)->default(0);
            
            // PENGELUARAN - Copy from Acuan Gaji + Absensi
            $table->decimal('bpjs_kesehatan_pengeluaran', 15, 2)->default(0);
            $table->decimal('bpjs_kecelakaan_kerja_pengeluaran', 15, 2)->default(0);
            $table->decimal('bpjs_kematian_pengeluaran', 15, 2)->default(0);
            $table->decimal('bpjs_jht_pengeluaran', 15, 2)->default(0);
            $table->decimal('bpjs_jp_pengeluaran', 15, 2)->default(0);
            $table->decimal('koperasi', 15, 2)->default(0);
            $table->decimal('kasbon', 15, 2)->default(0);
            $table->decimal('umroh', 15, 2)->default(0);
            $table->decimal('kurban', 15, 2)->default(0);
            $table->decimal('mutabaah', 15, 2)->default(0);
            $table->decimal('potongan_absensi', 15, 2)->default(0); // From Absensi
            $table->decimal('potongan_kehadiran', 15, 2)->default(0);
            
            // ADJUSTMENTS - JSON untuk setiap field
            // Format: {"field_name": {"nominal": 1000000, "type": "+", "description": "Bonus"}}
            $table->json('adjustments')->nullable();
            
            // TOTALS
            $table->decimal('total_pendapatan', 15, 2)->default(0);
            $table->decimal('total_pengeluaran', 15, 2)->default(0);
            $table->decimal('gaji_bersih', 15, 2)->default(0);
            
            // Status
            $table->enum('status', ['draft', 'preview', 'approved'])->default('draft');
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('acuan_gaji_id')->references('id_acuan')->on('acuan_gaji')->onDelete('cascade');
            
            $table->unique(['karyawan_id', 'periode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slip_gaji');
        Schema::dropIfExists('hitung_gaji');
    }
};

        
        // Recreate slip_gaji table
        Schema::create('slip_gaji', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hitung_gaji_id')->constrained('hitung_gaji')->onDelete('cascade');
            $table->foreignId('karyawan_id')->constrained('karyawan', 'id_karyawan')->onDelete('cascade');
            $table->string('periode');
            $table->json('data_gaji'); // All salary data in JSON
            $table->decimal('total_pendapatan', 15, 2);
            $table->decimal('total_pengeluaran', 15, 2);
            $table->decimal('gaji_bersih', 15, 2);
            $table->timestamp('generated_at');
            $table->foreignId('generated_by')->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->unique(['karyawan_id', 'periode']);
        });
