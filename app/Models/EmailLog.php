<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\MassPrunable;

class EmailLog extends Model
{
    use MassPrunable;
    protected $fillable = [
        'recipient',
        'subject',
        'body',
        'headers',
        'status',
        'error_message',
    ];

    protected $casts = [
        'headers' => 'array',
    ];

    /**
     * Get the prunable model query.
     */
    public function prunable(): \Illuminate\Database\Eloquent\Builder
    {
        return static::where('created_at', '<=', now()->subDays(30));
    }
}
