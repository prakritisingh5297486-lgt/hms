<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HospitalSetting extends Model
{
    protected $fillable = [
        'hospital_name',
        'phone',
        'address',
        'logo',
        'favicon', 

        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_encryption',
        'mail_username'
    ];
}