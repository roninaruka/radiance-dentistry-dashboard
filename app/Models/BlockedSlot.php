<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedSlot extends Model
{
    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'is_full_day',
        'location_id',
        'reason',
    ];

    protected $casts = [
        'date' => 'date',
        'is_full_day' => 'boolean',
    ];

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
