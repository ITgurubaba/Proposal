<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE service_fields 
            MODIFY field_type 
            ENUM('text','number','select','date','date_range') 
            NOT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         DB::statement("
            ALTER TABLE service_fields 
            MODIFY field_type 
            ENUM('text','number','select','date') 
            NOT NULL
        ");
    }
};
