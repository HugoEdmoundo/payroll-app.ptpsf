<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salary_templates', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['standard', 'status'])->default('standard');
            $table->string('employee_status', 50)->default('Kontrak');
            $table->string('jenis_karyawan', 100)->default('');
            $table->string('jabatan', 100)->default('');
            $table->string('lokasi_kerja', 100);
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->decimal('tunjangan_operasional', 15, 2)->default(0);
            $table->decimal('tunjangan_prestasi', 15, 2)->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->unique(['type', 'employee_status', 'jenis_karyawan', 'jabatan', 'lokasi_kerja'], 'salary_templates_unique');
        });

        // Migrate data from pengaturan_gaji -> salary_templates (type=standard)
        if (Schema::hasTable('pengaturan_gaji')) {
            $rows = DB::table('pengaturan_gaji')->get();
            foreach ($rows as $row) {
                DB::table('salary_templates')->insert([
                    'type' => 'standard',
                    'employee_status' => 'Kontrak',
                    'jenis_karyawan' => $row->jenis_karyawan ?? '',
                    'jabatan' => $row->jabatan ?? '',
                    'lokasi_kerja' => $row->lokasi_kerja ?? '',
                    'gaji_pokok' => $row->gaji_pokok ?? 0,
                    'tunjangan_operasional' => $row->tunjangan_operasional ?? 0,
                    'tunjangan_prestasi' => $row->tunjangan_prestasi ?? 0,
                    'keterangan' => $row->keterangan ?? null,
                    'created_at' => $row->created_at ?? now(),
                    'updated_at' => $row->updated_at ?? now(),
                ]);
            }
        }

        // Migrate data from pengaturan_gaji_status_pegawai -> salary_templates (type=status)
        if (Schema::hasTable('pengaturan_gaji_status_pegawai')) {
            $rows = DB::table('pengaturan_gaji_status_pegawai')->get();
            foreach ($rows as $row) {
                DB::table('salary_templates')->insert([
                    'type' => 'status',
                    'employee_status' => $row->status_pegawai ?? '',
                    'jenis_karyawan' => '',
                    'jabatan' => '',
                    'lokasi_kerja' => $row->lokasi_kerja ?? '',
                    'gaji_pokok' => $row->gaji_pokok ?? 0,
                    'tunjangan_operasional' => 0,
                    'tunjangan_prestasi' => 0,
                    'keterangan' => $row->keterangan ?? null,
                    'created_at' => $row->created_at ?? now(),
                    'updated_at' => $row->updated_at ?? now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('salary_templates');
    }
};
