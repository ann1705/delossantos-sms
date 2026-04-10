<?php

namespace App\Models;

use App\Models\ApplicationReview;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ApplicantData extends Model
{
    use HasFactory;

   protected $table = 'applicant_data';

    protected $fillable = [
        'user_id', 'applicant_photo', 'last_name', 'first_name', 'middle_name',
        'maiden_name', 'dob', 'sex', 'place_of_birth', 'pob_zip_code',
        'permanent_address', 'zip_code', 'citizenship', 'tribal_membership',
        'mobile_number', 'email_address', 'disability_type', 'school_name',
        'school_id_number', 'school_address', 'school_sector', 'course', 'year_level',
        'father_status', 'father_name', 'father_address', 'father_occupation',
        'mother_status', 'mother_name', 'mother_address', 'mother_occupation',
        'total_income', 'siblings_count', 'has_assistance', 'signature_path',
        'indigency_certificate', 'enrollment_proof',
        'date_accomplished',
        'application_status',
    ];


    /**
     * Relationship back to the User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Application review records for this applicant.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ApplicationReview::class);
    }

    /**
     * Latest review record for this applicant.
     */
    public function latestReview(): HasOne
    {
        return $this->hasOne(ApplicationReview::class)->latestOfMany();
    }
}
