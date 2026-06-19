<?php

namespace App\Filament\Rt\Resources\JenisIuranWargas;

use App\Filament\Rt\Resources\JenisIuranWargas\Pages\CreateJenisIuranWarga;
use App\Filament\Rt\Resources\JenisIuranWargas\Pages\EditJenisIuranWarga;
use App\Filament\Rt\Resources\JenisIuranWargas\Pages\ListJenisIuranWargas;
use App\Filament\Rt\Resources\JenisIuranWargas\Pages\ViewJenisIuranWarga;
use App\Filament\Rt\Resources\JenisIuranWargas\Schemas\JenisIuranWargaForm;
use App\Filament\Rt\Resources\JenisIuranWargas\Schemas\JenisIuranWargaInfolist;
use App\Filament\Rt\Resources\JenisIuranWargas\Tables\JenisIuranWargasTable;
use App\Models\JenisIuranWarga;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class JenisIuranWargaResource extends Resource
{
    protected static ?string $model = JenisIuranWarga::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $recordTitleAttribute = "jenis_iuran";

    protected static string|UnitEnum|null $navigationGroup = "Data Master";

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = "Kelola Jenis Iuran";

    protected static ?string $modelLabel = "Jenis Iuran";

    protected static ?string $pluralModelLabel = "Data Jenis Iuran";

    public static function form(Schema $schema): Schema
    {
        return JenisIuranWargaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JenisIuranWargasTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return JenisIuranWargaInfolist::configure($schema);
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
            "id_rt",
            auth()->user()?->rt?->id,
        );
    }

    public static function getPages(): array
    {
        return [
            "index" => ListJenisIuranWargas::route("/"),
            "create" => CreateJenisIuranWarga::route("/create"),
            "view" => ViewJenisIuranWarga::route("/{record}"),
            "edit" => EditJenisIuranWarga::route("/{record}/edit"),
        ];
    }
}
