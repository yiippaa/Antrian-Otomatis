<?php

namespace App\Filament\Resources\QueueReports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QueueReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('report_date')
                    ->required(),
                Select::make('polyclinic_id')
                    ->relationship('polyclinic', 'name')
                    ->required(),
                TextInput::make('total_queue')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_done')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_cancelled')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_no_show')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_bpjs')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_umum')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('avg_waiting_time')
                    ->numeric()
                    ->default(null),
                TextInput::make('avg_service_time')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
