<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationReview extends Model
{
    use HasFactory;

    protected $table = 'application_reviews';

    protected $fillable = [
        'applicant_data_id',
        'new_application_status',
        'admin_remarks',
        'evaluated_by',
        'admin_check_cor',
        'admin_check_indigency',
        'regional_coordinator',
    ];

    public function applicantData(): BelongsTo
    {
        return $this->belongsTo(ApplicantData::class);
    }
}
