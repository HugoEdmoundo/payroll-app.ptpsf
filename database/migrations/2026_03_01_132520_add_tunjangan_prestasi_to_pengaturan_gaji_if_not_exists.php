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
        if (Schema::hasTable('pengaturan_gaji') && !Schema::hasColumn('pengaturan_gaji', 'tunjangan_prestasi')) {
            Schema::table('pengaturan_gaji', function (Blueprint $table) {
                $table->decimal('tunjangan_prestasi', 15, 2)->default(0)->after('tunjangan_operasional');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pengaturan_gaji') && Schema::hasColumn('pengaturan_gaji', 'tunjangan_prestasi')) {
            Schema::table('pengaturan_gaji', function (Blueprint $table) {
                $table->dropColumn('tunjangan_prestasi');
            });
        }
    }
};
