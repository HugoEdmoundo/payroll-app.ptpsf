<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Update NKI structure:
     * - Kemampuan: 20%
     * - Kontribusi 1: 20%
     * - Kontribusi 2: 40%
     * - Kedisiplinan: 20%
     * Total: 100%
     */
    public function up(): void
    {
        Schema::table('nki', function (Blueprint $table) {
            // Rename kontribusi to kontribusi_1
            $table->renameColumn('kontribusi', 'kontribusi_1');
            
            // Remove lainnya column
            $table->dropColumn('lainnya');
        });
        
        Schema::table('nki', function (Blueprint $table) {
            // Add kontribusi_2 after kontribusi_1
            $table->decimal('kontribusi_2', 5, 2)->default(0)->after('kontribusi_1')->comment('Max 100.00');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nki', function (Blueprint $table) {
            // Remove kontribusi_2
            $table->dropColumn('kontribusi_2');
            
            // Rename back
            $table->renameColumn('kontribusi_1', 'kontribusi');
            
            // Add back lainnya
            $table->decimal('lainnya', 5, 2)->default(0);
        });
    }
};
