<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scholarship_forms', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // e.g., "UniFAST-TDP Annex 1"
            $table->string('file_path'); // Location in storage
            $table->string('version')->default('2022'); // Form version from your PDF
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarship_forms');
    }
};
