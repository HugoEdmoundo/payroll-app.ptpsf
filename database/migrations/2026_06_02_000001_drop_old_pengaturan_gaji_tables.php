<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('pengaturan_gaji_status_pegawai');
        Schema::dropIfExists('pengaturan_gaji');
    }

    public function down(): void
    {
        // Cannot recreate without full schema — data lives in salary_templates now
    }
};
