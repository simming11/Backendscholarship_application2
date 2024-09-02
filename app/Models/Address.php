<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses'; // กำหนดชื่อตาราง
    protected $primaryKey = 'AddressesID'; // Primary key ของตาราง

        // กำหนดให้ ApplicationID เป็น string
        protected $keyType = 'string';
        public $incrementing = false; // ปิดการเพิ่มค่าอัตโนมัติ

    protected $fillable = [
        'ApplicationID',
        'AddressLine',
        'Subdistrict',
        'province',
        'District',
        'PostalCode',
        'Type',
    ];

    /**
     * กำหนดความสัมพันธ์กับตาราง applications
     */
    public function application()
    {
        return $this->belongsTo(ApplicationInternal::class, 'ApplicationID', 'ApplicationID');
    }
}
