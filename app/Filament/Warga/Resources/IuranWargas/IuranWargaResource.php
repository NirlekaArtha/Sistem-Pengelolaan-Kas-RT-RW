<?php

namespace App\Filament\Warga\Resources\IuranWargas;

use App\Filament\Warga\Resources\IuranWargas\Pages\CreateIuranWarga;
use App\Filament\Warga\Resources\IuranWargas\Pages\EditIuranWarga;
use App\Filament\Warga\Resources\IuranWargas\Pages\ListIuranWargas;
use App\Filament\Warga\Resources\IuranWargas\Pages\ViewIuranWarga;
use App\Filament\Warga\Resources\IuranWargas\Schemas\IuranWargaForm;
use App\Filament\Warga\Resources\IuranWargas\Schemas\IuranWargaInfolist;
use App\Filament\Warga\Resources\IuranWargas\Tables\IuranWargasTable;
use App\Filament\Warga\Resources\IuranWargas\Widgets\IuranWargaOverview;
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

    protected static ?string $navigationLabel = "Iuran Saya";

    protected static ?string $recordTitleAttribute = "periode";

    protected static string|UnitEnum|null $navigationGroup = "Iuran";

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = "Iuran";

    protected static ?string $pluralModelLabel = "Riwayat Iuran";

    public static function form(Schema $schema): Schema
    {
        return IuranWargaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return IuranWargaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return IuranWargasTable::configure($table);
    }

    /**
     * Scope records to the currently authenticated warga only.
     */
    public static function getEloquentQuery(): Builder
    {
        $warga = auth()->user()?->warga;

        return parent::getEloquentQuery()->when(
            $warga,
            fn(Builder $q) => $q->where("id_warga", $warga->id),
        );
    }

    public static function getWidgets(): array
    {
        return [IuranWargaOverview::class];
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
            "index" => ListIuranWargas::route("/"),
            "create" => CreateIuranWarga::route("/create"),
            "view" => ViewIuranWarga::route("/{record}"),
            "edit" => EditIuranWarga::route("/{record}/edit"),
        ];
    }
}
