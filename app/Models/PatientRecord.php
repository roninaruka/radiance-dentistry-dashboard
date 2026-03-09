<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientRecord extends Model
{
    protected $fillable = [
        'patient_id',
        'type',
        'content',
        'attachments',
        'record_date',
    ];

    protected $casts = [
        'attachments' => 'array',
        'record_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
