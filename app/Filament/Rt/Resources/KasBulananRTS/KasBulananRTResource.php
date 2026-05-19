<?php

namespace App\Filament\Rt\Resources\KasBulananRTS;

use App\Filament\Rt\Resources\KasBulananRTS\Pages\CreateKasBulananRT;
use App\Filament\Rt\Resources\KasBulananRTS\Pages\EditKasBulananRT;
use App\Filament\Rt\Resources\KasBulananRTS\Pages\ListKasBulananRTS;
use App\Filament\Rt\Resources\KasBulananRTS\Pages\ViewKasBulananRT;
use App\Filament\Rt\Resources\KasBulananRTS\Schemas\KasBulananRTForm;
use App\Filament\Rt\Resources\KasBulananRTS\Schemas\KasBulananRTInfolist;
use App\Filament\Rt\Resources\KasBulananRTS\Tables\KasBulananRTSTable;
use App\Models\KasBulananRT;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KasBulananRTResource extends Resource
{
    protected static ?string $model = KasBulananRT::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'periode';

    public static function form(Schema $schema): Schema
    {
        return KasBulananRTForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KasBulananRTInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KasBulananRTSTable::configure($table);
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
            'index' => ListKasBulananRTS::route('/'),
            'create' => CreateKasBulananRT::route('/create'),
            'view' => ViewKasBulananRT::route('/{record}'),
            'edit' => EditKasBulananRT::route('/{record}/edit'),
        ];
    }
}
