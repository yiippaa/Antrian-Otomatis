<?php

namespace App\Filament\Resources\Queues\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class QueueForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            DatePicker::make('queue_date')
                ->label('Tanggal Antrian')
                ->required()
                ->default(now()),

            Select::make('polyclinic_id')
                ->label('Poli')
                ->relationship('polyclinic', 'name')
                ->required()
                ->searchable()
                ->preload(),

            Select::make('patient_id')
                ->label('Pasien')
                ->relationship('patient', 'name')
                ->required()
                ->searchable()
                ->preload(),

            Select::make('counter_id')
                ->label('Loket (Opsional)')
                ->relationship('counter', 'name')
                ->searchable()
                ->preload()
                ->nullable(),

            // ===== FIELD OTOMATIS (READ ONLY) =====

            TextInput::make('display_code')
                ->label('Nomor Antrian')
                ->disabled()
                ->dehydrated(false)
                ->helperText('Otomatis dibuat oleh sistem'),

            TextInput::make('patient_type')
                ->label('Jenis Pasien')
                ->disabled()
                ->dehydrated(false),

            TextInput::make('status')
                ->label('Status')
                ->disabled()
                ->dehydrated(false),
        ]);
    }
}
