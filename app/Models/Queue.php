<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Queue extends Model
{
    protected $fillable = [
        'queue_date',
        'polyclinic_id',
        'patient_id',
        'patient_type',   // snapshot
        'number',
        'display_code',
        'status',
        'counter_id',
        'called_at',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'queue_date'  => 'date',
        'called_at'   => 'datetime',
        'started_at'  => 'datetime',
        'finished_at' => 'datetime',
    ];

    public const STATUS_WAITING   = 'waiting';
    public const STATUS_CALLED    = 'called';
    public const STATUS_SERVING   = 'serving';
    public const STATUS_DONE      = 'done';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_NO_SHOW   = 'no_show';

    public function polyclinic(): BelongsTo
    {
        return $this->belongsTo(Polyclinic::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function counter(): BelongsTo
    {
        return $this->belongsTo(Counter::class);
    }
}
