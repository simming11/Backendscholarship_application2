<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationFile extends Model
{
    use HasFactory;

    protected $table = 'application_files';
    protected $primaryKey = 'FilesID';

    protected $fillable = [
        'ApplicationID',
        'Application_EtID',
        'DocumentName',
        'DocumentType',
        'FilePath',
    ];

    // ความสัมพันธ์กับ ApplicationInternal
    public function internalApplication()
    {
        return $this->belongsTo(ApplicationInternal::class, 'ApplicationID', 'ApplicationID');
    }

    // ความสัมพันธ์กับ ApplicationsExternal
    public function externalApplication()
    {
        return $this->belongsTo(ApplicationsExternal::class, 'Application_EtID', 'Application_EtID');
    }
}
