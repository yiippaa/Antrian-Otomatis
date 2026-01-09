<?php

namespace App\Filament\Resources\QueueReports\Pages;

use App\Filament\Resources\QueueReports\QueueReportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditQueueReport extends EditRecord
{
    protected static string $resource = QueueReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
