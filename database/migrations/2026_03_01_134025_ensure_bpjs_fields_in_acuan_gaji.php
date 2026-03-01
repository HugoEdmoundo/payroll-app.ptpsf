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
        Schema::table('acuan_gaji', function (Blueprint $table) {
            // Add BPJS fields if they don't exist
            if (!Schema::hasColumn('acuan_gaji', 'bpjs_kesehatan')) {
                $table->decimal('bpjs_kesehatan', 15, 2)->default(0)->after('gaji_pokok');
            }
            if (!Schema::hasColumn('acuan_gaji', 'bpjs_kecelakaan_kerja')) {
                $table->decimal('bpjs_kecelakaan_kerja', 15, 2)->default(0)->after('bpjs_kesehatan');
            }
            if (!Schema::hasColumn('acuan_gaji', 'bpjs_kematian')) {
                $table->decimal('bpjs_kematian', 15, 2)->default(0)->after('bpjs_kecelakaan_kerja');
            }
            if (!Schema::hasColumn('acuan_gaji', 'bpjs_jht')) {
                $table->decimal('bpjs_jht', 15, 2)->default(0)->after('bpjs_kematian');
            }
            if (!Schema::hasColumn('acuan_gaji', 'bpjs_jp')) {
                $table->decimal('bpjs_jp', 15, 2)->default(0)->after('bpjs_jht');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acuan_gaji', function (Blueprint $table) {
            $table->dropColumn([
                'bpjs_kesehatan',
                'bpjs_kecelakaan_kerja',
                'bpjs_kematian',
                'bpjs_jht',
                'bpjs_jp',
            ]);
        });
    }
};
