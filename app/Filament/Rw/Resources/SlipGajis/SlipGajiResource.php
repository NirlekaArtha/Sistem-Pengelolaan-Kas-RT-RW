<?php

namespace App\Filament\Rw\Resources\SlipGajis;

use App\Filament\Rw\Resources\SlipGajis\Pages\CreateSlipGaji;
use App\Filament\Rw\Resources\SlipGajis\Pages\EditSlipGaji;
use App\Filament\Rw\Resources\SlipGajis\Pages\ListSlipGajis;
use App\Filament\Rw\Resources\SlipGajis\Pages\ViewSlipGaji;
use App\Filament\Rw\Resources\SlipGajis\Schemas\SlipGajiForm;
use App\Filament\Rw\Resources\SlipGajis\Schemas\SlipGajiInfolist;
use App\Filament\Rw\Resources\SlipGajis\Tables\SlipGajisTable;
use App\Filament\Rw\Resources\SlipGajis\Widgets\SlipGajiOverview;
use App\Models\SlipGaji;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class SlipGajiResource extends Resource
{
    protected static ?string $model = SlipGaji::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = "Laporan & Rekap";

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = "id_petugas";

    protected static ?string $navigationLabel = "Slip Gaji";

    protected static ?string $modelLabel = "Slip Gaji";

    protected static ?string $pluralModelLabel = "Slip Gaji";

    public static function form(Schema $schema): Schema
    {
        return SlipGajiForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return SlipGajiInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SlipGajisTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
                //
            ];
    }
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas("petugas", function (
            $query,
        ) {
            $query->where("id_rw", auth()->user()?->rw?->id);
        });
    }
    public static function getWidgets(): array
    {
        return [SlipGajiOverview::class];
    }
    public static function getPages(): array
    {
        return [
            "index" => ListSlipGajis::route("/"),
            "create" => CreateSlipGaji::route("/create"),
            "view" => ViewSlipGaji::route("/{record}"),
            "edit" => EditSlipGaji::route("/{record}/edit"),
        ];
    }
}
