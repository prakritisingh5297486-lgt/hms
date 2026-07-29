<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'age',
        'gender',
        'disease',
        'blood_group',
        'number',
        'address',
        'profile'
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function appointments(): HasMany
    {

        return $this->hasMany(Appointment::class);
    }
    public function medicalRecords(): HasMany
    {
        return $this->hasMany(MedicalRecord::class);
    }
    public function consultations()
    {
        return $this->hasMany(Consultation::class);
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
    public function labDocuments(): HasMany
    {
        return $this->hasMany(LabDocuments::class);
    }
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
    // public function invoiceItems() : HasMany{
    //     return $this->hasMany(InvoiceItem::class);
    // }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function labReports()
    {
        return $this->hasMany(LabReport::class);
    }
    public function getProfilePhotoUrlAttribute(){
        $photo =$this->profile;
        if(!empty($photo)){
            if(str_starts_with($photo,'http://') || 
                str_starts_with($photo,'https://')){
                 return $photo;
            }
            $possiblePaths= [
                $photo,
                'super-admin/profile/'.$photo,
                'doctors/profile/'.$photo,
                'uploads/profile/'.$photo,
                'uploads/settings/'.$photo,
                'uploads/profile_photos/'.$photo,
                'patients/'.$photo

                ];

                foreach($possiblePaths as $path){
                    
                    if(file_exists(public_path($path))){
                        return asset($path);
                    }
                }
        }
        $name = $this->user->name?:'Patient';
        return 'https://ui-avatars.com/api/?name='.urlencode($name).
        '&background=0D8ABC&color=fff&bold=true&rounded=true';

    }
}
