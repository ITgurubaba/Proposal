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
        Schema::table('users', function (Blueprint $table) {

            $table->enum('user_type',['user','admin','sub_admin'])->default('user')->after('id');
            $table->string('gender')->after('id');
            $table->string('last_name')->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->string('country')->nullable()->after('phone');
            $table->string('state')->nullable()->after('phone');
            $table->string('city')->nullable()->after('phone');
            $table->string('zipcode')->nullable()->after('phone');
            $table->string('address',500)->nullable()->after('phone');
            $table->boolean('status')->default(true)->after('password');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
