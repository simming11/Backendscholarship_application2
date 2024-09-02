<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipDocument extends Model
{
    use HasFactory;

    protected $primaryKey = 'DocumentID'; // Define the primary key column
    protected $fillable = ['ScholarshipID', 'DocumentText', 'IsActive']; // Mass assignable fields

    // Define the relationship with Scholarship model
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'ScholarshipID', 'ScholarshipID');
    }
}
