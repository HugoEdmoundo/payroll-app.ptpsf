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
        Schema::create('jabatan_jenis_karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_karyawan');
            $table->string('jabatan');
            $table->timestamps();
            
            // Unique constraint untuk kombinasi jenis_karyawan dan jabatan
            $table->unique(['jenis_karyawan', 'jabatan'], 'unique_jenis_jabatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatan_jenis_karyawan');
    }
};
