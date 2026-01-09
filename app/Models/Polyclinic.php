<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Polyclinic extends Model
{
    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function counters(): HasMany
    {
        return $this->hasMany(Counter::class);
    }

    public function queues(): HasMany
    {
        return $this->hasMany(Queue::class);
    }

    protected static function booted(): void
    {
        static::created(function (self $polyclinic) {
            // Auto-generate code: POL-0001 (pakai ID biar aman dari bentrok)
            if (empty($polyclinic->code)) {
                $polyclinic->code = 'POL-' . str_pad((string) $polyclinic->id, 4, '0', STR_PAD_LEFT);
                $polyclinic->saveQuietly();
            }
        });
    }
}
