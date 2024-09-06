<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    protected $primaryKey = 'ScholarshipID';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'ScholarshipName',
        'Year',               // Academic year
        'Num_scholarship',     // Number of scholarships
        'Minimum_GPA',         // Minimum GPA required
        'YearLevel',           // Year level qualification (nullable)
        'TypeID',              // Foreign key for the scholarship type
        'StartDate',           // Start date for the scholarship
        'EndDate',             // End date for the scholarship
        'CreatedBy',           // Who created the scholarship (foreign key for academic user)
        'AnnouncementFile',    // File path for the announcement
    ];

    // Relationships

    // Relationship with the academic who created the scholarship
    public function creator()
    {
        return $this->belongsTo(Academic::class, 'CreatedBy', 'AcademicID');
    }

    // Relationship with the scholarship type
    public function type()
    {
        return $this->belongsTo(ScholarshipType::class, 'TypeID', 'TypeID');
    }

    // Relationship for qualifications related to the scholarship
    public function qualifications()
    {
        return $this->hasMany(ScholarshipQualification::class, 'ScholarshipID', 'ScholarshipID');
    }

    // Relationship for scholarship-related documents
    public function documents()
    {
        return $this->hasMany(ScholarshipDocument::class, 'ScholarshipID', 'ScholarshipID');
    }

    // Relationship for courses related to the scholarship
    public function courses()
    {
        return $this->hasMany(ScholarshipCourse::class, 'ScholarshipID', 'ScholarshipID');
    }

    // Relationship for scholarship-related files (including announcement files)
    public function files()
    {
        return $this->hasMany(ScholarshipFile::class, 'ScholarshipID', 'ScholarshipID');
    }

    // Relationship for scholarship-related images
    public function images()
    {
        return $this->hasMany(ScholarshipImage::class, 'ScholarshipID', 'ScholarshipID');
    }
}
