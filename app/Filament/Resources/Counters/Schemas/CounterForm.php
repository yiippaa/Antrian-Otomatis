<?php

namespace App\Filament\Resources\Counters\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CounterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')
                ->label('Kode Loket')
                ->disabled()
                ->dehydrated(false)
                ->helperText('Kode otomatis terisi setelah data dibuat.'),

            TextInput::make('name')
                ->label('Nama Loket')
                ->required()
                ->maxLength(100),

            Select::make('polyclinic_id')
                ->label('Poli (Opsional)')
                ->relationship('polyclinic', 'name')
                ->searchable()
                ->preload()
                ->nullable()
                ->helperText('Kosongkan jika loket bersifat umum.'),

            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ]);
    }
}
