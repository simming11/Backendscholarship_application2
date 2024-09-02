<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipCourse extends Model
{
    use HasFactory;

    // Explicitly defining the table name
    protected $table = 'scholarship_course';
    protected $primaryKey = 'id'; // ใช้ 'id' เป็น primary key
    public $incrementing = true;  // Indicates if the IDs are auto-incrementing
    public $timestamps = true;    // Enable created_at and updated_at columns

    // Specifying the fillable fields
    protected $fillable = [
        'ScholarshipID',
        'CourseName',
    ];

    /**
     * Define the relationship with the Scholarship model.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'ScholarshipID', 'ScholarshipID');
    }
}
