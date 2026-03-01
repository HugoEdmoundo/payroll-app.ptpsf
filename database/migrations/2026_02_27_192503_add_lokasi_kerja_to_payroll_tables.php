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
        // Add lokasi_kerja to acuan_gaji
        if (Schema::hasTable('acuan_gaji') && !Schema::hasColumn('acuan_gaji', 'lokasi_kerja')) {
            Schema::table('acuan_gaji', function (Blueprint $table) {
                $table->string('lokasi_kerja')->nullable()->after('id_karyawan');
            });
        }

        // Add lokasi_kerja to hitung_gaji
        if (Schema::hasTable('hitung_gaji') && !Schema::hasColumn('hitung_gaji', 'lokasi_kerja')) {
            Schema::table('hitung_gaji', function (Blueprint $table) {
                $table->string('lokasi_kerja')->nullable()->after('karyawan_id');
            });
        }

        // Add lokasi_kerja to slip_gaji (only if table exists)
        if (Schema::hasTable('slip_gaji') && !Schema::hasColumn('slip_gaji', 'lokasi_kerja')) {
            Schema::table('slip_gaji', function (Blueprint $table) {
                $table->string('lokasi_kerja')->nullable()->after('id_karyawan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('acuan_gaji') && Schema::hasColumn('acuan_gaji', 'lokasi_kerja')) {
            Schema::table('acuan_gaji', function (Blueprint $table) {
                $table->dropColumn('lokasi_kerja');
            });
        }

        if (Schema::hasTable('hitung_gaji') && Schema::hasColumn('hitung_gaji', 'lokasi_kerja')) {
            Schema::table('hitung_gaji', function (Blueprint $table) {
                $table->dropColumn('lokasi_kerja');
            });
        }

        if (Schema::hasTable('slip_gaji') && Schema::hasColumn('slip_gaji', 'lokasi_kerja')) {
            Schema::table('slip_gaji', function (Blueprint $table) {
                $table->dropColumn('lokasi_kerja');
            });
        }
    }
};
