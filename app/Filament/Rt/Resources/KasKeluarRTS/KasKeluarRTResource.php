<?php

namespace App\Filament\Rt\Resources\KasKeluarRTS;

use App\Filament\Rt\Resources\KasKeluarRTS\Pages\CreateKasKeluarRT;
use App\Filament\Rt\Resources\KasKeluarRTS\Pages\EditKasKeluarRT;
use App\Filament\Rt\Resources\KasKeluarRTS\Pages\ListKasKeluarRTS;
use App\Filament\Rt\Resources\KasKeluarRTS\Pages\ViewKasKeluarRT;
use App\Filament\Rt\Resources\KasKeluarRTS\Schemas\KasKeluarRTForm;
use App\Filament\Rt\Resources\KasKeluarRTS\Schemas\KasKeluarRTInfolist;
use App\Filament\Rt\Resources\KasKeluarRTS\Tables\KasKeluarRTSTable;
use App\Models\KasKeluarRT;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KasKeluarRTResource extends Resource
{
    protected static ?string $model = KasKeluarRT::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'penerima';

    public static function form(Schema $schema): Schema
    {
        return KasKeluarRTForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KasKeluarRTInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KasKeluarRTSTable::configure($table);
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
            'index' => ListKasKeluarRTS::route('/'),
            'create' => CreateKasKeluarRT::route('/create'),
            'view' => ViewKasKeluarRT::route('/{record}'),
            'edit' => EditKasKeluarRT::route('/{record}/edit'),
        ];
    }
}
