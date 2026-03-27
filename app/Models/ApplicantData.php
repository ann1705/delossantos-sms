<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantData extends Model
{
    use HasFactory;

   protected $table = 'applicant_data';

    protected $fillable = [
        'user_id', 'applicant_photo', 'last_name', 'first_name', 'middle_name',
        'maiden_name', 'dob', 'sex', 'place_of_birth', 'pob_zip_code',
        'permanent_address', 'zip_code', 'citizenship', 'tribal_membership',
        'mobile_number', 'email_address', 'disability_type', 'school_name',
        'school_id_number', 'school_address', 'school_sector',
        'father_status', 'father_name', 'father_address', 'father_occupation',
        'mother_status', 'mother_name', 'mother_address', 'mother_occupation',
        'total_income', 'siblings_count', 'has_assistance', 'signature_path',
        'indigency_certificate', 'enrollment_proof',
        'date_accomplished',
        'application_status',
        'admin_remarks',
        'evaluated_by',
        'admin_check_cor',
        'admin_check_indigency',
        'regional_coordinator',
    ];


    /**
     * Relationship back to the User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
