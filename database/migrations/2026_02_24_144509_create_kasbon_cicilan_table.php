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
        Schema::create('kasbon_cicilan', function (Blueprint $table) {
            $table->id('id_cicilan');
            $table->unsignedBigInteger('id_kasbon');
            $table->integer('cicilan_ke'); // 1, 2, 3, ...
            $table->string('periode'); // YYYY-MM (periode pembayaran)
            $table->decimal('nominal_cicilan', 15, 2);
            $table->date('tanggal_bayar')->nullable(); // Tanggal actual pembayaran
            $table->enum('status', ['Pending', 'Terbayar'])->default('Pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->foreign('id_kasbon')->references('id_kasbon')->on('kasbon')->onDelete('cascade');
            
            // Unique constraint: satu kasbon tidak bisa punya 2 cicilan dengan nomor yang sama
            $table->unique(['id_kasbon', 'cicilan_ke']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kasbon_cicilan');
    }
};
