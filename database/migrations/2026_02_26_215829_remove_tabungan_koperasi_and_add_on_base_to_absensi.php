<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Remove tabungan_koperasi from acuan_gaji
        if (Schema::hasColumn('acuan_gaji', 'tabungan_koperasi')) {
            Schema::table('acuan_gaji', function (Blueprint $table) {
                $table->dropColumn('tabungan_koperasi');
            });
        }
        
        // Remove tabungan_koperasi from hitung_gaji
        if (Schema::hasColumn('hitung_gaji', 'tabungan_koperasi')) {
            Schema::table('hitung_gaji', function (Blueprint $table) {
                $table->dropColumn('tabungan_koperasi');
            });
        }
        
        // Add on_base to absensi
        if (!Schema::hasColumn('absensi', 'on_base')) {
            Schema::table('absensi', function (Blueprint $table) {
                $table->integer('on_base')->default(0)->after('on_site');
            });
        }
    }

    public function down(): void
    {
        // Add back tabungan_koperasi to acuan_gaji
        if (!Schema::hasColumn('acuan_gaji', 'tabungan_koperasi')) {
            Schema::table('acuan_gaji', function (Blueprint $table) {
                $table->decimal('tabungan_koperasi', 15, 2)->default(0)->after('bpjs_jp_pengeluaran');
            });
        }
        
        // Add back tabungan_koperasi to hitung_gaji
        if (!Schema::hasColumn('hitung_gaji', 'tabungan_koperasi')) {
            Schema::table('hitung_gaji', function (Blueprint $table) {
                $table->decimal('tabungan_koperasi', 15, 2)->default(0)->after('bpjs_jp_pengeluaran');
            });
        }
        
        // Remove on_base from absensi
        if (Schema::hasColumn('absensi', 'on_base')) {
            Schema::table('absensi', function (Blueprint $table) {
                $table->dropColumn('on_base');
            });
        }
    }
};
