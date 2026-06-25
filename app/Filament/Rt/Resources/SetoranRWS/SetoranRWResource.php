<?php

namespace App\Filament\Rt\Resources\SetoranRWS;

use App\Enums\SetoranStatusValidasi;
use App\Filament\Rt\Resources\SetoranRWS\Pages\CreateSetoranRW;
use App\Filament\Rt\Resources\SetoranRWS\Pages\EditSetoranRW;
use App\Filament\Rt\Resources\SetoranRWS\Pages\ListSetoranRWS;
use App\Filament\Rt\Resources\SetoranRWS\Pages\ViewSetoranRW;
use App\Filament\Rt\Resources\SetoranRWS\Schemas\SetoranRWForm;
use App\Filament\Rt\Resources\SetoranRWS\Schemas\SetoranRWInfolist;
use App\Filament\Rt\Resources\SetoranRWS\Tables\SetoranRWSTable;
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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'periode';

    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Pengajuan Setoran RW';

    protected static ?string $modelLabel = 'Setoran RW';

    protected static ?string $pluralModelLabel = 'Data Setoran RW';

    public static function form(Schema $schema): Schema
    {
        return SetoranRWForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SetoranRWSTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SetoranRWInfolist::configure($schema);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where(
            'id_rt',
            auth()->user()?->rt?->id,
        );
    }

    public static function canEdit($record): bool
    {
        return $record?->status_validasi === SetoranStatusValidasi::PENDING;
    }

    public static function canDelete($record): bool
    {
        return $record?->status_validasi === SetoranStatusValidasi::PENDING;
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
