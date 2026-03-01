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
        if (Schema::hasTable('pengaturan_gaji') && !Schema::hasColumn('pengaturan_gaji', 'tunjangan_operasional')) {
            Schema::table('pengaturan_gaji', function (Blueprint $table) {
                $table->decimal('tunjangan_operasional', 15, 2)->default(0)->after('gaji_pokok');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('pengaturan_gaji') && Schema::hasColumn('pengaturan_gaji', 'tunjangan_operasional')) {
            Schema::table('pengaturan_gaji', function (Blueprint $table) {
                $table->dropColumn('tunjangan_operasional');
            });
        }
    }
};
