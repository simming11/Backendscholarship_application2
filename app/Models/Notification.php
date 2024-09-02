<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    // กำหนดชื่อ primary key
    protected $primaryKey = 'NotificationID';

    // กำหนด fillable fields เพื่อป้องกัน mass assignment
    protected $fillable = [
        'StudentID',
        'Message',
        'SentDate',
    ];

    // กำหนดความสัมพันธ์กับตาราง students
    public function student()
    {
        return $this->belongsTo(Student::class, 'StudentID', 'StudentID');
    }
}
