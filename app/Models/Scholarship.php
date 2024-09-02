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
        'Year', // Academic year
        'Num_scholarship', // Number of scholarships
        'Minimum_GPA', // Minimum GPA required
        'YearLevel',
        'TypeID',
        'StartDate',
        'EndDate',
        'CreatedBy', // Who created the scholarship
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(Academic::class, 'CreatedBy', 'AcademicID');
    }

    public function type()
    {
        return $this->belongsTo(ScholarshipType::class, 'TypeID', 'TypeID');
    }

    public function Qualifications()
    {
        return $this->hasMany(ScholarshipQualification::class, 'ScholarshipID', 'ScholarshipID');
    }

    public function documents()
    {
        return $this->hasMany(ScholarshipDocument::class, 'ScholarshipID', 'ScholarshipID');
    }

    public function courses()
    {
        return $this->hasMany(ScholarshipCourse::class, 'ScholarshipID', 'ScholarshipID');
    }

    public function files()
    {
        return $this->hasMany(ScholarshipFile::class, 'ScholarshipID', 'ScholarshipID');
    }
    public function images()
    {
        return $this->hasMany(ScholarshipImage::class, 'ScholarshipID', 'ScholarshipID');
    }
}
