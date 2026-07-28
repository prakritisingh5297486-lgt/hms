<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabDocuments extends Model
{
    protected $fillable = [
        'patient_id',
        'document_name',
        'file_path',
        'category',
        'file_size'
    ];
    public function patient():BelongsTo{
        return $this->belongsTo(Patient::class);
    }
}
