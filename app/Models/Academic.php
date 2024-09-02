<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Use Authenticatable for authentication support
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Academic extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Set the primary key to match the schema
    protected $primaryKey = 'AcademicID';
    public $incrementing = false; // AcademicID is not auto-incrementing
    protected $keyType = 'string'; // Set the primary key type to string

    // Define the fillable attributes
    protected $fillable = [
        'AcademicID', // Added to match primary key
        'FirstName',
        'LastName',
        'Position',
        'Email',
        'Phone',
        'Password',
    ];

    // Automatically hash the password
    protected $hidden = [
        'Password',
        'remember_token',
    ];

    // Define relationships
    public function scholarships()
    {
        return $this->hasMany(Scholarship::class, 'CreatedBy', 'AcademicID');
    }

    // Override method for Laravel Authentication to use the correct password field
    public function getAuthPassword()
    {
        return $this->Password;
    }
}
