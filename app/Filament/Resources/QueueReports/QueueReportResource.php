<?php

namespace App\Filament\Resources\QueueReports;

use App\Filament\Resources\QueueReports\Pages\ListQueueReports;
use App\Filament\Resources\QueueReports\Tables\QueueReportsTable;
use App\Models\QueueReport;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QueueReportResource extends Resource
{
    protected static ?string $model = QueueReport::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Laporan';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;
    protected static ?string $navigationLabel = 'Laporan Antrian';


    protected static ?string $recordTitleAttribute = 'report_date';

    // ❌ FORM TIDAK DIGUNAKAN
    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return QueueReportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListQueueReports::route('/'),
        ];
    }
}
