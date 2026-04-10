<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('application_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_data_id')->constrained('applicant_data')->onDelete('cascade');
            $table->string('new_application_status')->default('pending');
            $table->text('admin_remarks')->nullable();
            $table->string('evaluated_by')->nullable();
            $table->boolean('admin_check_cor')->default(false);
            $table->boolean('admin_check_indigency')->default(false);
            $table->string('regional_coordinator')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('application_reviews');
    }
};
