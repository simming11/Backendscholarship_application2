<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // อย่าลืม import Str class

class ApplicationsExternal extends Model
{
    use HasFactory;

    protected $table = 'applications_external'; // ชื่อตารางในฐานข้อมูล
    protected $primaryKey = 'Application_EtID'; // ชื่อคอลัมน์ Primary Key
    public $incrementing = false; // ปิดการเพิ่มค่าอัตโนมัติ
    protected $keyType = 'string'; // กำหนดประเภทของ Primary Key เป็น string

    protected $fillable = [
        'StudentID',
        'ScholarshipID',
        'Status',
        'ApplicationDate',
    ];

    protected $casts = [
        'ApplicationDate' => 'date', // กำหนดให้ ApplicationDate เป็นประเภท date
    ];

    protected static function boot()
    {
        parent::boot();

        // Automatically generate Application_EtID with prefix EX
        static::creating(function ($model) {
            if (empty($model->Application_EtID)) {
                $model->Application_EtID = 'EX-' . strtoupper(Str::random(10));
            }
        });
    }

    // ความสัมพันธ์กับ ApplicationFile
    public function applicationFiles()
    {
        return $this->hasMany(ApplicationFile::class, 'Application_EtID', 'Application_EtID');
    }

    // ความสัมพันธ์กับ Student
    public function student()
    {
        return $this->belongsTo(Student::class, 'StudentID', 'StudentID');
    }

    // ความสัมพันธ์กับ Scholarship
    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'ScholarshipID', 'ScholarshipID');
    }
}
