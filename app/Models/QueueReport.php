<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QueueReport extends Model
{
    protected $fillable = [
        'report_date',
        'polyclinic_id',
        'total_queue',
        'total_done',
        'total_cancelled',
        'total_no_show',
        'total_bpjs',
        'total_umum',
        'avg_waiting_time',
        'avg_service_time',
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    public function polyclinic(): BelongsTo
    {
        return $this->belongsTo(Polyclinic::class);
    }
}
