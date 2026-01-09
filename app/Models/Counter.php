<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Counter extends Model
{
    protected $fillable = [
        'code',
        'name',
        'polyclinic_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function polyclinic(): BelongsTo
    {
        return $this->belongsTo(Polyclinic::class);
    }

    public function queues(): HasMany
    {
        return $this->hasMany(Queue::class);
    }

    protected static function booted(): void
    {
        static::created(function (self $counter) {
            if (empty($counter->code)) {
                $counter->code = 'CTR-' . str_pad((string) $counter->id, 4, '0', STR_PAD_LEFT);
                $counter->saveQuietly();
            }
        });
    }
}
