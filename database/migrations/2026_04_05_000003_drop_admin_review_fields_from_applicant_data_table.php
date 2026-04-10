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
        Schema::table('applicant_data', function (Blueprint $table) {
            if (Schema::hasColumn('applicant_data', 'admin_remarks')) {
                $table->dropColumn([
                    'admin_remarks',
                    'evaluated_by',
                    'admin_check_cor',
                    'admin_check_indigency',
                    'regional_coordinator',
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applicant_data', function (Blueprint $table) {
            $table->text('admin_remarks')->nullable();
            $table->string('evaluated_by')->nullable();
            $table->boolean('admin_check_cor')->default(false);
            $table->boolean('admin_check_indigency')->default(false);
            $table->string('regional_coordinator')->nullable();
        });
    }
};
