<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activities';
    protected $primaryKey = 'ActivitiesID'; // Primary key ของตาราง

    protected $fillable = [
        'AcademicYear',
        'ActivityName',
        'Position',
        'ApplicationID', // เพิ่ม ApplicationID ให้สามารถบันทึกได้
    ];

    /**
     * กำหนดความสัมพันธ์กับตาราง ApplicationInternal
     */
    public function application()
    {
        return $this->belongsTo(ApplicationInternal::class, 'ApplicationID', 'ApplicationID');
    }
}
