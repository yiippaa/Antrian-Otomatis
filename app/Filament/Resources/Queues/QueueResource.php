<?php

namespace App\Filament\Resources\Queues;

use App\Filament\Resources\Queues\Pages\CreateQueue;
use App\Filament\Resources\Queues\Pages\EditQueue;
use App\Filament\Resources\Queues\Pages\ListQueues;
use App\Filament\Resources\Queues\Schemas\QueueForm;
use App\Filament\Resources\Queues\Tables\QueuesTable;
use App\Models\Queue;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QueueResource extends Resource
{
    protected static ?string $model = Queue::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Transaksi';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;
    protected static ?string $navigationLabel = 'Queues';


    protected static ?string $recordTitleAttribute = 'display_code';

    public static function form(Schema $schema): Schema
    {
        return QueueForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QueuesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListQueues::route('/'),
            'create' => CreateQueue::route('/create'),
            'edit'   => EditQueue::route('/{record}/edit'),
        ];
    }
}
