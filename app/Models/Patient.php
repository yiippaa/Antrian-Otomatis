<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'code',
        'name',
        'phone',
        'patient_type',   // BPJS / UMUM
        'bpjs_number',
    ];

    protected $casts = [
        // kalau mau strict, bisa ditambah nanti
    ];

    public function queues(): HasMany
    {
        return $this->hasMany(Queue::class);
    }

    protected static function booted(): void
    {
        static::created(function (self $patient) {
            if (empty($patient->code)) {
                $patient->code = 'PSN-' . str_pad((string) $patient->id, 4, '0', STR_PAD_LEFT);
                $patient->saveQuietly();
            }
        });
    }
}
