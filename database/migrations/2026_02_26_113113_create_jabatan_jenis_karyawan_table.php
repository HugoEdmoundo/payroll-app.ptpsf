<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jabatan_jenis_karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_karyawan');
            $table->string('jabatan');
            $table->timestamps();
            
            // Unique constraint to prevent duplicate combinations
            $table->unique(['jenis_karyawan', 'jabatan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jabatan_jenis_karyawan');
    }
};
