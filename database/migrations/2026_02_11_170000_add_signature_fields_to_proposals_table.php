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
        Schema::table('proposals', function (Blueprint $table) {
            // Add 'approved' to status enum
            $table->enum('status', ['draft', 'sent', 'accepted', 'rejected', 'approved'])->default('draft')->change();

            // Add signature fields
            $table->text('signature_image')->nullable()->after('status');
            $table->timestamp('signed_at')->nullable()->after('signature_image');
            $table->string('signed_pdf_path')->nullable()->after('signed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->enum('status', ['draft', 'sent', 'accepted', 'rejected'])->default('draft')->change();
            $table->dropColumn(['signature_image', 'signed_at', 'signed_pdf_path']);
        });
    }
};
