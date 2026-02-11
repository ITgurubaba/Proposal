<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('proposal_services', function (Blueprint $table) {
        $table->integer('sort_order')->default(0)->after('data');
    });
}

public function down()
{
    Schema::table('proposal_services', function (Blueprint $table) {
        $table->dropColumn('sort_order');
    });
}

};
