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
        Schema::create('service_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->string('field_name');   // vat_scheme, employees, etc
            $table->string('field_label');  // VAT Scheme, No of Employees
            $table->enum('field_type', ['text', 'number', 'select', 'date']);
            $table->json('options')->nullable(); // for dropdown
            $table->boolean('is_required')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_fields');
    }
};
