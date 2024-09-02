<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sibling extends Model
{
    use HasFactory;

    protected $table = 'siblings'; // กำหนดชื่อตาราง
    protected $primaryKey = 'siblingsID'; // Primary key ของตาราง

    public $incrementing = true; // เปิดการเพิ่มค่าอัตโนมัติ
    protected $keyType = 'int'; // กำหนดประเภทของ primary key เป็น integer

    protected $fillable = [
        'ApplicationID',
        'PrefixName',
        'Fname',
        'Lname',
        'Occupation',
        'EducationLevel',
        'Income',
        'Status',
    ];

    /**
     * กำหนดความสัมพันธ์กับตาราง application_internals
     */
    public function application()
    {
        return $this->belongsTo(ApplicationInternal::class, 'ApplicationID', 'ApplicationID');
    }
}
