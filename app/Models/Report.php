<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $primaryKey = 'ReportID'; // Primary key is ReportID
    public $incrementing = true;        // Incrementing is true
    protected $keyType = 'int';         // Primary key is of type int

    protected $fillable = [
        'ReportContent',
        'CreatedDate',
        'ApplicationID',
        'Application_EtID',
    ];

    // Relationships

    // Relates to internal applications
    public function internalApplication()
    {
        return $this->belongsTo(ApplicationInternal::class, 'ApplicationID', 'ApplicationID');
    }

    // Relates to external applications
    public function externalApplication()
    {
        return $this->belongsTo(ApplicationsExternal::class, 'Application_EtID', 'Application_EtID');
    }
}
