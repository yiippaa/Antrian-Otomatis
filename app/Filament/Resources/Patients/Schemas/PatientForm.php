<?php

namespace App\Filament\Resources\Patients\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('code')
                ->label('Kode Pasien')
                ->disabled()              // tidak bisa diedit
                ->dehydrated(false)       // tidak ikut tersimpan dari form
                ->helperText('Kode otomatis terisi setelah data dibuat.'),

            TextInput::make('name')
                ->label('Nama Pasien')
                ->required()
                ->maxLength(150),

            TextInput::make('phone')
                ->label('No. HP')
                ->tel()
                ->maxLength(30)
                ->default(null),

            Select::make('patient_type')
                ->label('Jenis Pasien')
                ->options([
                    'BPJS' => 'BPJS',
                    'UMUM' => 'UMUM',
                ])
                ->required()
                ->reactive(), // supaya field bpjs_number bisa show/hide secara realtime

            TextInput::make('bpjs_number')
                ->label('No. BPJS')
                ->maxLength(30)
                ->default(null)
                // tampil hanya kalau BPJS
                ->visible(fn ($get) => $get('patient_type') === 'BPJS')
                // wajib kalau BPJS, kalau UMUM nggak wajib
                ->required(fn ($get) => $get('patient_type') === 'BPJS'),
        ]);
    }
}
