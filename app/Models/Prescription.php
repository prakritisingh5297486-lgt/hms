<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'consultation_id',
        'patient_id',
        'medicine_name',
        'dosage',
        'duration',
        'prescribed_by',
        'status'
    ];
    public function consultation()
    {
        return $this->belongsTo(Consultation::class);
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'prescribed_by');
    }
}
