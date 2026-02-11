<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id('id_karyawan');
            $table->string('nama_karyawan');
            $table->date('join_date');
            $table->integer('masa_kerja')->default(0);
            $table->string('jabatan');
            $table->string('lokasi_kerja');
            $table->string('jenis_karyawan');
            $table->string('status_pegawai');
            $table->string('npwp')->nullable();
            $table->string('bpjs_kesehatan_no')->nullable();
            $table->string('bpjs_tk_no')->nullable();
            $table->string('no_rekening');
            $table->string('bank');
            $table->string('status_perkawinan')->nullable();
            $table->string('nama_istri')->nullable();
            $table->integer('jumlah_anak')->default(0);
            $table->string('no_telp_istri')->nullable();
            $table->string('status_karyawan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};