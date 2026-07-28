<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'invoice_no',
        'patient_id',
        'doctor_id',
        'appointment_id',
        'amount',
        'discount',
        'tax',
        'total_amount',
        'payment_method',
        'payment_status',
        'transaction_id',
        'payment_date',
        'notes',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Payment belongs to Patient
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Payment belongs to Doctor
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Payment belongs to Appointment
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}