<?php

namespace App\Filament\Resources\QueueReports\Pages;

use App\Filament\Resources\QueueReports\QueueReportResource;
use App\Services\QueueReportService;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListQueueReports extends ListRecords
{
    protected static string $resource = QueueReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generate')
                ->label('Generate Laporan Hari Ini')
                ->icon('heroicon-o-arrow-path')
                ->requiresConfirmation()
                ->action(fn () =>
                    app(QueueReportService::class)
                        ->generateByDate(Carbon::today())
                ),
        ];
    }
}
