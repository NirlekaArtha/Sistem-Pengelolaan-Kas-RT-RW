<?php

namespace App\Filament\Rw\Resources\KasKeluarRWS;

use App\Filament\Rw\Resources\KasKeluarRWS\Pages\CreateKasKeluarRW;
use App\Filament\Rw\Resources\KasKeluarRWS\Pages\EditKasKeluarRW;
use App\Filament\Rw\Resources\KasKeluarRWS\Pages\ListKasKeluarRWS;
use App\Filament\Rw\Resources\KasKeluarRWS\Pages\ViewKasKeluarRW;
use App\Filament\Rw\Resources\KasKeluarRWS\Schemas\KasKeluarRWForm;
use App\Filament\Rw\Resources\KasKeluarRWS\Schemas\KasKeluarRWInfolist;
use App\Filament\Rw\Resources\KasKeluarRWS\Tables\KasKeluarRWSTable;
use App\Models\KasKeluarRW;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KasKeluarRWResource extends Resource
{
    protected static ?string $model = KasKeluarRW::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'penerima';

    public static function form(Schema $schema): Schema
    {
        return KasKeluarRWForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KasKeluarRWInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KasKeluarRWSTable::configure($table);
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
            'index' => ListKasKeluarRWS::route('/'),
            'create' => CreateKasKeluarRW::route('/create'),
            'view' => ViewKasKeluarRW::route('/{record}'),
            'edit' => EditKasKeluarRW::route('/{record}/edit'),
        ];
    }
}
