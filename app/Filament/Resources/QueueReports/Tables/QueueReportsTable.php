<?php

namespace App\Filament\Resources\QueueReports\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QueueReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('report_date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                TextColumn::make('polyclinic.name')
                    ->label('Poli')
                    ->searchable(),

                TextColumn::make('total_queue')
                    ->label('Total'),

                TextColumn::make('total_done')
                    ->label('Selesai'),

                TextColumn::make('total_cancelled')
                    ->label('Batal'),

                TextColumn::make('total_no_show')
                    ->label('No-show'),

                TextColumn::make('total_bpjs')
                    ->label('BPJS'),

                TextColumn::make('total_umum')
                    ->label('UMUM'),

                TextColumn::make('avg_waiting_time')
                    ->label('Avg Tunggu (detik)')
                    ->placeholder('-'),

                TextColumn::make('avg_service_time')
                    ->label('Avg Layanan (detik)')
                    ->placeholder('-'),
            ])
            ->defaultSort('report_date', 'desc');
    }
}
