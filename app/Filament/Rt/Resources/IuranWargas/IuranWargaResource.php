<?php

namespace App\Filament\Rt\Resources\IuranWargas;

use App\Filament\Rt\Resources\IuranWargas\Pages\CreateIuranWarga;
use App\Filament\Rt\Resources\IuranWargas\Pages\EditIuranWarga;
use App\Filament\Rt\Resources\IuranWargas\Pages\ListIuranWargas;
use App\Filament\Rt\Resources\IuranWargas\Pages\ViewIuranWarga;
use App\Filament\Rt\Resources\IuranWargas\Schemas\IuranWargaForm;
use App\Filament\Rt\Resources\IuranWargas\Schemas\IuranWargaInfolist;
use App\Filament\Rt\Resources\IuranWargas\Tables\IuranWargasTable;
use App\Models\IuranWarga;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class IuranWargaResource extends Resource
{
    protected static ?string $model = IuranWarga::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $recordTitleAttribute = 'periode_label';

    protected static string|UnitEnum|null $navigationGroup = 'Transaksi';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Iuran Warga';

    protected static ?string $modelLabel = 'Iuran Warga';

    protected static ?string $pluralModelLabel = 'Data Iuran Warga';

    public static function form(Schema $schema): Schema
    {
        return IuranWargaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IuranWargasTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return IuranWargaInfolist::configure($schema);
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
            'id_rt',
            auth()->user()?->rt?->id,
        );
    }

    public static function getPages(): array
    {
        return [
            'index' => ListIuranWargas::route('/'),
            'create' => CreateIuranWarga::route('/create'),
            'view' => ViewIuranWarga::route('/{record}'),
            'edit' => EditIuranWarga::route('/{record}/edit'),
        ];
    }
}
