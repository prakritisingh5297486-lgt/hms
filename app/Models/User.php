<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// class User extends Authenticatable
// {
    /** @use HasFactory<UserFactory> */
    // use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
    //  * @var list<string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    //     'role',
    //     'image'
    // ];

    // public function doctor(): \Illuminate\Database\Eloquent\Relations\HasOne{
    //     return $this->hasOne(Doctor::class);
    // }
    // public function patient(): \Illuminate\Database\Eloquent\Relations\HasOne{
    //     return $this->hasOne(Patient::class);
    // }
    
    /**
     * The attributes that should be hidden for serialization.
     *
    //  * @var list<string>
    //  */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    /**
     * Get the attributes that should be cast.
     *
//      * @return array<string, string>
//      */
//     protected function casts(): array
//     {
//         return [
//             'email_verified_at' => 'datetime',
//             'password' => 'hashed',
//         ];
//     }
// }


class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
        'profile_photo',
    ];

    public function doctor(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Doctor::class);
    }

    public function patient(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Patient::class);
    }
    public function getProfilePhotoUrlAttribute()
    {
        // Super Admin Image
        if (!empty($this->image) && file_exists(public_path('uploads/profile/' . $this->image))) {
            return asset('uploads/profile/' . $this->image);
        }

        // Doctor Image
        if ($this->role == 'doctor' && $this->doctor && !empty($this->doctor->profile_photo)) {

            if (file_exists(public_path('doctors/profile/' . $this->doctor->profile_photo))) {
                return asset('doctors/profile/' . $this->doctor->profile_photo);
            }
        }

        // Patient Image
        if ($this->role == 'patient' && $this->patient && !empty($this->patient->profile)) {

            if (file_exists(public_path('patients/' . $this->patient->profile))) {
                return asset('patients/' . $this->patient->profile);
            }
        }

        // Default Avatar
        return 'https://ui-avatars.com/api/?name=' .
            urlencode($this->name ?? 'User') .
            '&background=0D8ABC&color=fff&bold=true&rounded=true';
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}