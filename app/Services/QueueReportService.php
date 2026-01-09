<?php

namespace App\Services;

use App\Models\Queue;
use App\Models\QueueReport;
use App\Models\Polyclinic;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class QueueReportService
{
    /**
     * Generate laporan antrian per tanggal
     */
    public function generateByDate(Carbon $date): void
    {
        $polyclinics = Polyclinic::query()->get();

        foreach ($polyclinics as $polyclinic) {

            $queues = Queue::query()
                ->whereDate('queue_date', $date)
                ->where('polyclinic_id', $polyclinic->id)
                ->get();

            if ($queues->isEmpty()) {
                continue;
            }

            // hitung total
            $totalQueue = $queues->count();

            $totalDone = $queues->where('status', 'done')->count();
            $totalCancelled = $queues->where('status', 'cancelled')->count();
            $totalNoShow = $queues->where('status', 'no_show')->count();

            // BPJS vs UMUM
            $totalBpjs = $queues->where('patient_type', 'BPJS')->count();
            $totalUmum = $queues->where('patient_type', 'UMUM')->count();

            // avg waktu tunggu (called_at - created_at)
            $avgWaitingTime = $queues
            ->filter(fn ($q) => $q->called_at)
            ->avg(fn ($q) => max(0, $q->created_at->diffInSeconds($q->called_at)));


            // avg durasi layanan (finished_at - started_at)
            $avgServiceTime = $queues
            ->filter(fn ($q) => $q->finished_at && $q->started_at)
            ->avg(fn ($q) => max(0, $q->started_at->diffInSeconds($q->finished_at)));


            // simpan / update laporan
            QueueReport::updateOrCreate(
                [
                    'report_date' => $date->toDateString(),
                    'polyclinic_id' => $polyclinic->id,
                ],
                [
                    'total_queue' => $totalQueue,
                    'total_done' => $totalDone,
                    'total_cancelled' => $totalCancelled,
                    'total_no_show' => $totalNoShow,
                    'total_bpjs' => $totalBpjs,
                    'total_umum' => $totalUmum,
                    'avg_waiting_time' => $avgWaitingTime ? (int) $avgWaitingTime : null,
                    'avg_service_time' => $avgServiceTime ? (int) $avgServiceTime : null,
                ]
            );
        }
    }
}
