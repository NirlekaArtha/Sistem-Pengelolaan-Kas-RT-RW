<?php

namespace App\Filament\Rw\Resources\RTS;

use App\Filament\Rw\Resources\RTS\Pages\CreateRT;
use App\Filament\Rw\Resources\RTS\Pages\EditRT;
use App\Filament\Rw\Resources\RTS\Pages\ListRTS;
use App\Filament\Rw\Resources\RTS\Schemas\RTForm;
use App\Filament\Rw\Resources\RTS\Tables\RTSTable;
use App\Filament\Rw\Resources\RTS\Widgets\RTOverview;
use App\Models\RT;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RTResource extends Resource
{
    protected static ?string $model = RT::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHomeModern;

    protected static string|UnitEnum|null $navigationGroup = "Data Master";

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = "nama";

    protected static ?string $navigationLabel = "Data RT";

    protected static ?string $modelLabel = "RT";

    protected static ?string $pluralModelLabel = "Data RT";

    public static function form(Schema $schema): Schema
    {
        return RTForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RTSTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
                //
            ];
    }

    /**
     * Scope tabel hanya ke data RT milik RW yang sedang login.
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->where(
            "id_rw",
            auth()->user()?->rw?->id,
        );
    }

    public static function getWidgets(): array
    {
        return [RTOverview::class];
    }

    public static function getPages(): array
    {
        return [
            "index" => ListRTS::route("/"),
            "create" => CreateRT::route("/create"),
            "edit" => EditRT::route("/{record}/edit"),
        ];
    }
}
