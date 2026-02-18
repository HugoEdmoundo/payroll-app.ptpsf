<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_status_pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Harian, OJT, Kontrak
            $table->integer('durasi_hari')->nullable(); // 14 untuk harian, 90 untuk OJT
            $table->text('keterangan')->nullable();
            $table->boolean('gunakan_nki')->default(false); // Kontrak = true
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_status_pegawai');
    }
};
