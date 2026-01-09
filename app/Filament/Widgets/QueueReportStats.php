<?php

namespace App\Filament\Widgets;

use App\Models\QueueReport;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QueueReportStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $todayReport = QueueReport::query()
            ->whereDate('report_date', now())
            ->get();

        $totalQueue = $todayReport->sum('total_queue');
        $totalDone = $todayReport->sum('total_done');
        $totalNoShow = $todayReport->sum('total_no_show');

        $avgWaiting = $todayReport->avg('avg_waiting_time');
        $avgService = $todayReport->avg('avg_service_time');

        return [
            Stat::make('Total Antrian', $totalQueue)
                ->icon('heroicon-o-queue-list'),

            Stat::make('Selesai', $totalDone)
                ->icon('heroicon-o-check-circle')
                ->color('success'),

            Stat::make('No-show', $totalNoShow)
                ->icon('heroicon-o-x-circle')
                ->color('danger'),

            Stat::make(
                'Avg Waktu Tunggu',
                $avgWaiting ? round($avgWaiting / 60, 1) . ' menit' : '-'
            )->icon('heroicon-o-clock'),

            Stat::make(
                'Avg Waktu Layanan',
                $avgService ? round($avgService / 60, 1) . ' menit' : '-'
            )->icon('heroicon-o-clock'),
        ];
    }
}
