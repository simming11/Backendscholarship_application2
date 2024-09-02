<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipImage extends Model
{
    use HasFactory;

    protected $table = 'scholarship_images';

    protected $primaryKey = 'ImageID';

    protected $fillable = [
        'ScholarshipID',
        'ImagePath',
        'Description',
    ];

    // Define the relationship to the Scholarship model
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'ScholarshipID', 'ScholarshipID');
    }
}
