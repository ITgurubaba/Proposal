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
        Schema::create('proposal_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained()->cascadeOnDelete();
            $table->string('content_type')->default('service'); // 'service' or 'other'
            $table->foreignId('source_content_id')->nullable(); // ID from service_contents or other_contents
            $table->string('title');
            $table->longText('content'); // Customized content for this proposal
            $table->foreignId('service_id')->nullable()->constrained()->nullOnDelete(); // For service content
            $table->foreignId('service_item_id')->nullable()->constrained('service_items')->nullOnDelete(); // For service item content
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_contents');
    }
};
