<?php

namespace App\Filament\Rw\Resources\RTS;

use App\Filament\Rw\Resources\RTS\Pages\CreateRT;
use App\Filament\Rw\Resources\RTS\Pages\EditRT;
use App\Filament\Rw\Resources\RTS\Pages\ListRTS;
use App\Filament\Rw\Resources\RTS\Pages\ViewRT;
use App\Filament\Rw\Resources\RTS\Schemas\RTForm;
use App\Filament\Rw\Resources\RTS\Schemas\RTInfolist;
use App\Filament\Rw\Resources\RTS\Tables\RTSTable;
use App\Models\RT;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RTResource extends Resource
{
    protected static ?string $model = RT::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return RTForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RTInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RTSTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRTS::route('/'),
            'create' => CreateRT::route('/create'),
            'view' => ViewRT::route('/{record}'),
            'edit' => EditRT::route('/{record}/edit'),
        ];
    }
}
