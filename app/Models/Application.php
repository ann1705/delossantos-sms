<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = [
        'user_id',
        'course',
        'year_level',
        'status',
        'remarks',
    ];

    /**
     * Define the relationship to the detailed applicant data.
     */
    public function applicantData()
    {
        // Assuming 'user_id' is the common column in both tables
        // If your foreign key is named differently, adjust accordingly
        return $this->belongsTo(ApplicantData::class, 'user_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
