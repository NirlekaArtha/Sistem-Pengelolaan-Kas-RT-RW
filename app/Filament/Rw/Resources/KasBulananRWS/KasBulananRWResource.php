<?php

namespace App\Filament\Rw\Resources\KasBulananRWS;

use App\Filament\Rw\Resources\KasBulananRWS\Pages\CreateKasBulananRW;
use App\Filament\Rw\Resources\KasBulananRWS\Pages\EditKasBulananRW;
use App\Filament\Rw\Resources\KasBulananRWS\Pages\ListKasBulananRWS;
use App\Filament\Rw\Resources\KasBulananRWS\Pages\ViewKasBulananRW;
use App\Filament\Rw\Resources\KasBulananRWS\Schemas\KasBulananRWForm;
use App\Filament\Rw\Resources\KasBulananRWS\Schemas\KasBulananRWInfolist;
use App\Filament\Rw\Resources\KasBulananRWS\Tables\KasBulananRWSTable;
use App\Models\KasBulananRW;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class KasBulananRWResource extends Resource
{
    protected static ?string $model = KasBulananRW::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static string|UnitEnum|null $navigationGroup = 'Laporan & Rekap';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Kas Bulanan';

    protected static ?string $modelLabel = 'Kas Bulanan';

    protected static ?string $pluralModelLabel = 'Kas Bulanan';

    protected static ?string $recordTitleAttribute = 'periode';

    public static function form(Schema $schema): Schema
    {
        return KasBulananRWForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KasBulananRWInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KasBulananRWSTable::configure($table);
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
        return [Widgets\KasBulananRWOverview::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKasBulananRWS::route('/'),
            'create' => CreateKasBulananRW::route('/create'),
            'view' => ViewKasBulananRW::route('/{record}'),
            'edit' => EditKasBulananRW::route('/{record}/edit'),
        ];
    }
}
