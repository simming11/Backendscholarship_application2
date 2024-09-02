<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // Use Authenticatable for authentication support
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Set the primary key to match the schema
    protected $primaryKey = 'StudentID';
    public $incrementing = false; // StudentID is not auto-incrementing
    protected $keyType = 'string'; // Set the primary key type to string

    // Define the fillable attributes
    protected $fillable = [
        'StudentID', 
        'Password',
        'FirstName',
        'LastName',
        'Email',
        'GPA',
        'Year_Entry', 
        'Religion',
        'PrefixName',
        'Phone',
        'DOB',
        'Course',
    ];

    // Automatically hash the password when setting it
    protected $hidden = [
        'Password',
        'remember_token',
    ];

    // Define relationships
    public function applications()
    {
        return $this->hasMany(ApplicationInternal::class, 'StudentID', 'StudentID');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'StudentID', 'StudentID');
    }

    public function lineNotifies()
    {
        return $this->hasMany(LineNotify::class, 'StudentID', 'StudentID');
    }

    // Override method for Laravel Authentication to use the correct password field
    public function getAuthPassword()
    {
        return $this->Password;
    }


}
