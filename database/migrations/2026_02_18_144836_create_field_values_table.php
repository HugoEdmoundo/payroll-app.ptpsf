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
        Schema::create('field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dynamic_field_id')->constrained()->onDelete('cascade');
            $table->string('entity_type'); // App\Models\Karyawan, App\Models\User
            $table->unsignedBigInteger('entity_id'); // ID of the entity
            $table->text('value')->nullable();
            $table->timestamps();
            
            $table->index(['entity_type', 'entity_id']);
            $table->unique(['dynamic_field_id', 'entity_type', 'entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('field_values');
    }
};
