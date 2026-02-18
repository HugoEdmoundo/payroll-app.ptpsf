<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_wilayah', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // CJ, EJ, WJ
            $table->string('nama'); // Central Java, East Java
            $table->text('keterangan')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_wilayah');
    }
};
