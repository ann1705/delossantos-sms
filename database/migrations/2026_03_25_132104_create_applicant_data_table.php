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
        Schema::create('applicant_data', function (Blueprint $table) {
            $table->id();
            // Link to the Users table
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // 1. Personal Information
            $table->string('applicant_photo')->nullable(); // Stores path: uploads/photos/filename.jpg
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('maiden_name')->nullable();
            $table->date('dob');
            $table->string('sex');
            $table->text('place_of_birth');
            $table->string('pob_zip_code'); // Birthplace Zip Code
            $table->text('permanent_address');
            $table->string('zip_code');     // Permanent Address Zip Code
            $table->string('citizenship');
            $table->string('tribal_membership')->nullable();
            $table->string('mobile_number');
            $table->string('email_address')->nullable();
            $table->string('disability_type')->nullable();

            // 2. Academic Information
            $table->string('school_name');
            $table->string('course')->nullable();
            $table->string('school_id_number')->nullable();
            $table->text('school_address');
            $table->string('school_sector'); // e.g., Public or Private
            $table->string('year_level')->nullable();

            // 3. Family Background
            $table->string('father_status')->nullable(); // e.g., Living/Deceased
            $table->string('father_name')->nullable();
            $table->text('father_address')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_status')->nullable();
            $table->string('mother_name')->nullable();
            $table->text('mother_address')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->decimal('total_income', 15, 2)->default(0.00);
            $table->integer('siblings_count')->default(0);
            $table->boolean('has_assistance')->default(false);

            // 4. Admin fields
            $table->string('application_status')->default('pending');
            $table->text('admin_remarks')->nullable();
            $table->string('evaluated_by')->nullable();
            $table->boolean('admin_check_cor')->default(false);
            $table->boolean('admin_check_indigency')->default(false);
            $table->string('regional_coordinator')->nullable();

            // 5. Attachments & Certification
            $table->string('enrollment_proof')->nullable();      // Stores path: uploads/docs/filename.pdf
            $table->string('indigency_certificate')->nullable(); // Stores path: uploads/docs/filename.pdf
            $table->string('signature_path')->nullable();        // Stores path: uploads/sigs/filename.png
            $table->date('date_accomplished');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicant_data');
    }
};
