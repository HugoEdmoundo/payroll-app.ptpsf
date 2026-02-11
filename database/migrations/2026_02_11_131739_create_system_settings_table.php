<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('system_settings')) {
            Schema::create('system_settings', function (Blueprint $table) {
                $table->id();
                $table->string('group');
                $table->string('key');
                $table->string('value');
                $table->integer('order')->default(0);
                $table->timestamps();
                
                $table->unique(['group', 'key']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};