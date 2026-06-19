<?php

namespace App\Filament\Rw\Resources\KasRWS;

use App\Filament\Rw\Resources\KasRWS\Pages\CreateKasRW;
use App\Filament\Rw\Resources\KasRWS\Pages\EditKasRW;
use App\Filament\Rw\Resources\KasRWS\Pages\ListKasRWS;
use App\Filament\Rw\Resources\KasRWS\Pages\ViewKasRW;
use App\Filament\Rw\Resources\KasRWS\Schemas\KasRWForm;
use App\Filament\Rw\Resources\KasRWS\Schemas\KasRWInfolist;
use App\Filament\Rw\Resources\KasRWS\Tables\KasRWSTable;
use App\Filament\Rw\Resources\KasRWS\Widgets\KasRWOverview;
use App\Models\KasRW;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class KasRWResource extends Resource
{
    protected static ?string $model = KasRW::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Kelola Kas Harian';

    protected static ?string $modelLabel = 'Kas Harian';

    protected static ?string $pluralModelLabel = 'Kelola Kas Harian';

    protected static ?string $recordTitleAttribute = 'sumber_tujuan';

    public static function form(Schema $schema): Schema
    {
        return KasRWForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KasRWInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KasRWSTable::configure($table);
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
        return [KasRWOverview::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKasRWS::route('/'),
            'create' => CreateKasRW::route('/create'),
            'view' => ViewKasRW::route('/{record}'),
            'edit' => EditKasRW::route('/{record}/edit'),
        ];
    }
}
