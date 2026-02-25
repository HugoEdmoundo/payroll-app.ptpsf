<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action'); // login, logout, create, update, delete, import, export, search, generate
            $table->string('module')->nullable(); // karyawan, acuan_gaji, hitung_gaji, etc
            $table->text('description')->nullable();
            $table->json('metadata')->nullable(); // additional data
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index('action');
            $table->index('module');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
