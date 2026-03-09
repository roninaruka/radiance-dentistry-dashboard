<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'date_of_birth',
        'address',
    ];

    public function records()
    {
        return $this->hasMany(PatientRecord::class)->latest();
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
