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

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Admin Full Name / Student Name [cite: 90, 97]
            $table->string('email')->unique(); // [cite: 91]
            $table->string('password'); // [cite: 89]
            $table->string('role')->default('student'); // 'admin' or 'student'
            $table->rememberToken();
            $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
