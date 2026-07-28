<?php

namespace App\Exports;

use App\Models\Appointment;
use Maatwebsite\Excel\Concerns\FromCollection;

class ReportExport implements FromCollection
{
    public function collection()
    {
        return Appointment::select(
            'id',
            'patient_id',
            'doctor_id',
            'appointment_date',
            'status'
        )->get();
    }
}