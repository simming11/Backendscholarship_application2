<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipFile extends Model
{
    use HasFactory;

    protected $table = 'scholarship_files';
    protected $primaryKey = 'FileID';
    public $incrementing = true;

    public $timestamps = false;  // Disable Eloquent timestamps

    protected $fillable = [
        'ScholarshipID',
        'FileType',
        'FilePath',
        'Description'
    ];

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'ScholarshipID', 'ScholarshipID');
    }
}
