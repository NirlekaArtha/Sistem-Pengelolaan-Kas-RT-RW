<?php

namespace App\Filament\Rt\Resources\KasRTS;

use App\Filament\Rt\Resources\KasRTS\Pages\CreateKasRT;
use App\Filament\Rt\Resources\KasRTS\Pages\EditKasRT;
use App\Filament\Rt\Resources\KasRTS\Pages\ListKasRTS;
use App\Filament\Rt\Resources\KasRTS\Pages\ViewKasRT;
use App\Filament\Rt\Resources\KasRTS\Schemas\KasRTForm;
use App\Filament\Rt\Resources\KasRTS\Schemas\KasRTInfolist;
use App\Filament\Rt\Resources\KasRTS\Tables\KasRTSTable;
use App\Models\KasRT;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class KasRTResource extends Resource
{
    protected static ?string $model = KasRT::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $recordTitleAttribute = 'sumber_tujuan';

    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Kelola Kas Harian';

    protected static ?string $modelLabel = 'Kas Harian';

    protected static ?string $pluralModelLabel = 'Data Kas Harian';

    public static function form(Schema $schema): Schema
    {
        return KasRTForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KasRTSTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KasRTInfolist::configure($schema);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where(
            'id_rt',
            auth()->user()?->rt?->id,
        );
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKasRTS::route('/'),
            'create' => CreateKasRT::route('/create'),
            'view' => ViewKasRT::route('/{record}'),
            'edit' => EditKasRT::route('/{record}/edit'),
        ];
    }
}
