<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScholarshipType extends Model
{
    use HasFactory;

    // Set the primary key
    protected $primaryKey = 'TypeID';

    // Specify the primary key type as integer
    protected $keyType = 'int';

    // Disable auto-incrementing since we are using the default behavior
    public $incrementing = true;

    // Define fillable fields to prevent mass assignment issues
    protected $fillable = [
        'TypeName',
    ];

    // Define the relationship with the scholarships table
    public function scholarships()
    {
        return $this->hasMany(Scholarship::class, 'TypeID', 'TypeID');
    }
}
