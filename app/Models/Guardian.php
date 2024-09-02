<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    protected $table = 'guardians'; // กำหนดชื่อตาราง
    protected $primaryKey = 'GuardiansID'; // Primary key ของตาราง
    
    public $incrementing = true; // เปิดการเพิ่มค่าอัตโนมัติ (ควรจะเปิดในกรณีที่ใช้ integer)
    protected $keyType = 'int'; // กำหนดประเภทของ primary key เป็น integer
    
    protected $fillable = [
        'ApplicationID',
        'PrefixName',
        'FirstName',
        'LastName',
        'Type',
        'Occupation',
        'Phone',
        'Income',
        'Age',
        'Status',
        'Workplace',
    ];

    /**
     * กำหนดความสัมพันธ์กับตาราง applications
     */
    public function application()
    {
        return $this->belongsTo(ApplicationInternal::class, 'ApplicationID', 'ApplicationID');
    }
}
