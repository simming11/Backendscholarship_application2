<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LineNotify extends Model
{
    use HasFactory;

    protected $primaryKey = 'LineNotifyID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'StudentID',
        'LineToken',
        'SentDate',
    ];

    /**
     * Get the student that owns the line notify.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'StudentID');
    }
}
