<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkExperience extends Model
{
    use HasFactory;

    protected $table = 'work_experiences'; // กำหนดชื่อตาราง
    protected $primaryKey = 'WorkexperiencesId'; // Primary key ของตาราง

    public $incrementing = true; // ให้เพิ่มค่าอัตโนมัติได้ (ตามที่ใช้งานใน migration)
    protected $keyType = 'int'; // กำหนดประเภทของ primary key เป็น integer

    protected $fillable = [
        'ApplicationID',
        'Name',
        'JobType',
        'Duration',
        'Earnings',
    ];

    /**
     * กำหนดความสัมพันธ์กับตาราง applications
     */
    public function application()
    {
        return $this->belongsTo(ApplicationInternal::class, 'ApplicationID', 'ApplicationID');
    }
}
