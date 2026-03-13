<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'reason',
        'appointment_date',
        'appointment_time',
        'patient_id',
        'doctor_id',
        'location_id',
        'status',
        'note',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
