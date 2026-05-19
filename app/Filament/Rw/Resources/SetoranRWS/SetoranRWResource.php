<?php

namespace App\Filament\Rw\Resources\SetoranRWS;

use App\Filament\Rw\Resources\SetoranRWS\Pages\CreateSetoranRW;
use App\Filament\Rw\Resources\SetoranRWS\Pages\EditSetoranRW;
use App\Filament\Rw\Resources\SetoranRWS\Pages\ListSetoranRWS;
use App\Filament\Rw\Resources\SetoranRWS\Pages\ViewSetoranRW;
use App\Filament\Rw\Resources\SetoranRWS\Schemas\SetoranRWForm;
use App\Filament\Rw\Resources\SetoranRWS\Schemas\SetoranRWInfolist;
use App\Filament\Rw\Resources\SetoranRWS\Tables\SetoranRWSTable;
use App\Models\SetoranRW;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SetoranRWResource extends Resource
{
    protected static ?string $model = SetoranRW::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'periode';

    public static function form(Schema $schema): Schema
    {
        return SetoranRWForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SetoranRWInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SetoranRWSTable::configure($table);
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
            'index' => ListSetoranRWS::route('/'),
            'create' => CreateSetoranRW::route('/create'),
            'view' => ViewSetoranRW::route('/{record}'),
            'edit' => EditSetoranRW::route('/{record}/edit'),
        ];
    }
}
