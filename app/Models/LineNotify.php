<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;  // ใช้ Carbon สำหรับจัดการวันที่

class LineNotify extends Model
{
    use HasFactory;

    protected $primaryKey = 'LineNotifyID'; // Primary key ใช้ auto-increment โดยค่าเริ่มต้น

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'AcademicID',
        'LineToken',
        'SentDate',
        'notify_client_id',
        'client_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'SentDate' => 'datetime',  // แปลง SentDate ให้เป็น datetime object
    ];

    /**
     * Relationship with Academic model.
     *
     * This defines the relationship between LineNotify and Academic models.
     */
    public function academic()
    {
        return $this->belongsTo(Academic::class, 'AcademicID', 'AcademicID');
    }

    /**
     * Accessor for SentDate to format the date in Thai timezone and format.
     */
    public function getSentDateAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Bangkok')->locale('th')->isoFormat('D MMMM YYYY H:mm:ss'); // วันที่ภาษาไทย + เวลาไทย
    }
}
