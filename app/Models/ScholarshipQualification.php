<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipQualification extends Model
{
    use HasFactory;

    protected $table = 'scholarship_qualifications'; // Explicitly defining the table name
    protected $primaryKey = 'QualificationID';      // Set the primary key
    public $incrementing = true;                    // Indicates if the IDs are auto-incrementing
    public $timestamps = false;                     // Disable created_at and updated_at columns

    protected $fillable = [
        'ScholarshipID',
        'QualificationText',
        'IsActive'
    ];

    /**
     * Define the relationship with the Scholarship model.
     */
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'ScholarshipID', 'ScholarshipID');
    }
}
