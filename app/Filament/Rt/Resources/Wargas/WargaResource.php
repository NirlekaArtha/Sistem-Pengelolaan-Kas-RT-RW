<?php

namespace App\Filament\Rt\Resources\Wargas;

use App\Filament\Rt\Resources\Wargas\Pages\CreateWarga;
use App\Filament\Rt\Resources\Wargas\Pages\EditWarga;
use App\Filament\Rt\Resources\Wargas\Pages\ListWargas;
use App\Filament\Rt\Resources\Wargas\Pages\ViewWarga;
use App\Filament\Rt\Resources\Wargas\Schemas\WargaForm;
use App\Filament\Rt\Resources\Wargas\Schemas\WargaInfolist;
use App\Filament\Rt\Resources\Wargas\Tables\WargasTable;
use App\Models\Warga;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class WargaResource extends Resource
{
    protected static ?string $model = Warga::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUpTray;

    protected static ?string $recordTitleAttribute = "nama_kepala_keluarga";

    protected static string|UnitEnum|null $navigationGroup = "Data Master";

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = "Kelola Warga";

    protected static ?string $modelLabel = "Warga (per KK)";

    protected static ?string $pluralModelLabel = "Data Warga (per KK)";

    public static function form(Schema $schema): Schema
    {
        return WargaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WargasTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return WargaInfolist::configure($schema);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where(
            "id_rt",
            auth()->user()?->rt?->id,
        );
    }

    public static function getPages(): array
    {
        return [
            "index" => ListWargas::route("/"),
            "create" => CreateWarga::route("/create"),
            "view" => ViewWarga::route("/{record}"),
            "edit" => EditWarga::route("/{record}/edit"),
        ];
    }
}
