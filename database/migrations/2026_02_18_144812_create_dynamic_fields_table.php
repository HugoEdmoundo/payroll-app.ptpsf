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
        Schema::create('dynamic_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->string('field_name'); // email, no_telp, custom_field_1
            $table->string('field_label'); // Email Address, Phone Number
            $table->string('field_type'); // text, email, number, select, textarea, date, checkbox, radio, file
            $table->text('field_options')->nullable(); // JSON for select/radio options
            $table->string('validation_rules')->nullable(); // required|email|max:255
            $table->string('default_value')->nullable();
            $table->text('help_text')->nullable();
            $table->string('placeholder')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_searchable')->default(false);
            $table->boolean('show_in_list')->default(false); // show in table list
            $table->boolean('show_in_form')->default(true);
            $table->integer('order')->default(0);
            $table->string('group')->nullable(); // untuk grouping fields
            $table->timestamps();
            
            $table->unique(['module_id', 'field_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dynamic_fields');
    }
};
