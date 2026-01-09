<?php

namespace App\Filament\Resources\Queues\Pages;

use App\Filament\Resources\Queues\QueueResource;
use App\Models\Patient;
use App\Models\Queue;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateQueue extends CreateRecord
{
    protected static string $resource = QueueResource::class;

    /**
     * Di sinilah seluruh logika antrian otomatis berada
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ambil data pasien
        $patient = Patient::query()->findOrFail($data['patient_id']);

        // Snapshot jenis pasien (BPJS / UMUM)
        $data['patient_type'] = $patient->patient_type;

        // Status awal selalu waiting
        $data['status'] = Queue::STATUS_WAITING ?? 'waiting';

        return DB::transaction(function () use ($data) {

            // Cari nomor terakhir berdasarkan:
            // tanggal + poli + jenis pasien
            $lastNumber = Queue::query()
                ->whereDate('queue_date', $data['queue_date'])
                ->where('polyclinic_id', $data['polyclinic_id'])
                ->where('patient_type', $data['patient_type'])
                ->lockForUpdate()
                ->max('number');

            $nextNumber = ((int) $lastNumber) + 1;

            $data['number'] = $nextNumber;

            // Prefix antrian
            $prefix = $data['patient_type'] === 'BPJS' ? 'B' : 'U';

            // Contoh: B-001 / U-002
            $data['display_code'] = $prefix . '-' . str_pad(
                (string) $nextNumber,
                3,
                '0',
                STR_PAD_LEFT
            );

            return $data;
        });
    }
}
