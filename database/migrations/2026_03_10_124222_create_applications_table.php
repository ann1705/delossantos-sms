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
       Schema::create('applications', function (Blueprint $table) {
            $table->id(); // PK application_id [cite: 103]
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Link to user
            $table->string('course'); // [cite: 100]
            $table->string('year_level'); // [cite: 101]
            $table->string('status')->default('Pending'); // [cite: 108]
            $table->text('remarks')->nullable(); // [cite: 109]
            $table->timestamps();

            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
