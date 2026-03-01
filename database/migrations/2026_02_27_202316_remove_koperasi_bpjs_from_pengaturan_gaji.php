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
        if (Schema::hasTable('pengaturan_gaji')) {
            Schema::table('pengaturan_gaji', function (Blueprint $table) {
                // Drop old BPJS and Koperasi fields (now in separate module)
                $columns = ['koperasi', 'bpjs_kesehatan', 'bpjs_ketenagakerjaan', 'bpjs_kecelakaan_kerja', 'bpjs_total'];
                
                foreach ($columns as $column) {
                    if (Schema::hasColumn('pengaturan_gaji', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pengaturan_gaji')) {
            Schema::table('pengaturan_gaji', function (Blueprint $table) {
                $table->decimal('koperasi', 15, 2)->default(0);
                $table->decimal('bpjs_kesehatan', 15, 2)->default(0);
                $table->decimal('bpjs_ketenagakerjaan', 15, 2)->default(0);
                $table->decimal('bpjs_kecelakaan_kerja', 15, 2)->default(0);
                $table->decimal('bpjs_total', 15, 2)->default(0);
            });
        }
    }
};
