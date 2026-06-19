<?php

namespace App\Filament\Rw\Resources\SetoranRWS;

use App\Filament\Rw\Resources\SetoranRWS\Pages\CreateSetoranRW;
use App\Filament\Rw\Resources\SetoranRWS\Pages\EditSetoranRW;
use App\Filament\Rw\Resources\SetoranRWS\Pages\ListSetoranRWS;
use App\Filament\Rw\Resources\SetoranRWS\Pages\ViewSetoranRW;
use App\Filament\Rw\Resources\SetoranRWS\Schemas\SetoranRWForm;
use App\Filament\Rw\Resources\SetoranRWS\Schemas\SetoranRWInfolist;
use App\Filament\Rw\Resources\SetoranRWS\Tables\SetoranRWSTable;
use App\Filament\Rw\Resources\SetoranRWS\Widgets\SetoranRWOverview;
use App\Models\SetoranRW;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class SetoranRWResource extends Resource
{
    protected static ?string $model = SetoranRW::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowDownTray;

    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'periode';

    protected static ?string $navigationLabel = 'Setoran RW';

    protected static ?string $modelLabel = 'Setoran';

    protected static ?string $pluralModelLabel = 'Setoran RW';

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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where(
            'id_rw',
            auth()->user()?->rw?->id,
        );
    }

    public static function getWidgets(): array
    {
        return [SetoranRWOverview::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSetoranRWS::route('/'),
            'create' => CreateSetoranRW::route('/create'),
            'edit' => EditSetoranRW::route('/{record}/edit'),
            'view' => ViewSetoranRW::route('/{record}/'),
        ];
    }
}
