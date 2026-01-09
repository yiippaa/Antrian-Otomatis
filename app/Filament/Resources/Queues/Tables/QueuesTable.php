<?php

namespace App\Filament\Resources\Queues\Tables;

use App\Models\Queue;
use App\Models\QueueReport;
use App\Services\TtsService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class QueuesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('queue_date')
                    ->label('Tanggal')
                    ->date()
                    ->sortable(),

                TextColumn::make('display_code')
                    ->label('No Antrian')
                    ->searchable(),

                TextColumn::make('patient.name')
                    ->label('Pasien')
                    ->searchable(),

                TextColumn::make('patient_type')
                    ->label('Jenis')
                    ->badge(),

                TextColumn::make('polyclinic.name')
                    ->label('Poli')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),

                TextColumn::make('counter.name')
                    ->label('Loket')
                    ->placeholder('-'),

                TextColumn::make('called_at')
                    ->label('Dipanggil')
                    ->dateTime()
                    ->placeholder('-'),

                TextColumn::make('started_at')
                    ->label('Mulai')
                    ->dateTime()
                    ->placeholder('-'),

                TextColumn::make('finished_at')
                    ->label('Selesai')
                    ->dateTime()
                    ->placeholder('-'),
            ])
            ->recordActions([
                Action::make('call')
                    ->label('Call')
                    ->visible(fn ($record) => $record->status === 'waiting')
                    ->action(function ($record) {
                        $record->update([
                            'status'    => 'called',
                            'called_at' => now(),
                        ]);
                    }),

                Action::make('start')
                    ->label('Start')
                    ->visible(fn ($record) => $record->status === 'called')
                    ->action(fn ($record) => $record->update([
                        'status' => 'serving',
                        'started_at' => now(),
                    ])),

                Action::make('finish')
                    ->label('Finish')
                    ->visible(fn ($record) => $record->status === 'serving')
                    ->action(fn ($record) => $record->update([
                        'status' => 'done',
                        'finished_at' => now(),
                    ])),

                Action::make('no_show')
                    ->label('No Show')
                    ->visible(fn ($record) => in_array($record->status, ['called']))
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->update([
                        'status' => 'no_show',
                    ])),
            ])
            ->toolbarActions([
                Action::make('reset_today')
                    ->label('Reset Antrian Hari Ini')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Reset antrian hari ini?')
                    ->modalDescription('Semua data antrian untuk hari ini akan dihapus. Aksi ini tidak bisa dibatalkan.')
                    ->action(function () {
                        Queue::query()
                            ->whereDate('queue_date', now())
                            ->delete();

                        QueueReport::query()
                            ->whereDate('report_date', now())
                            ->delete();
                    }),

                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }
}
