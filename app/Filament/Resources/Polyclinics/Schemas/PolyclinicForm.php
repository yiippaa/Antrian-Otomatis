<?php

namespace App\Filament\Resources\Polyclinics\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PolyclinicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')
                ->label('Kode Poli')
                ->disabled()
                ->dehydrated(false)
                ->helperText('Kode otomatis terisi setelah data dibuat.'),

            TextInput::make('name')
                ->label('Nama Poli')
                ->required()
                ->maxLength(100),

            Toggle::make('is_active')
                ->label('Aktif')
                ->default(true),
        ]);
    }
}
