<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabReport extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'report_name',
        'report_type',
        'file',
        'file_size',
        'status'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
