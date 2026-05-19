<?php

namespace App\Filament\Rw\Resources\Kasbons;

use App\Filament\Rw\Resources\Kasbons\Pages\CreateKasbon;
use App\Filament\Rw\Resources\Kasbons\Pages\EditKasbon;
use App\Filament\Rw\Resources\Kasbons\Pages\ListKasbons;
use App\Filament\Rw\Resources\Kasbons\Pages\ViewKasbon;
use App\Filament\Rw\Resources\Kasbons\Schemas\KasbonForm;
use App\Filament\Rw\Resources\Kasbons\Schemas\KasbonInfolist;
use App\Filament\Rw\Resources\Kasbons\Tables\KasbonsTable;
use App\Models\Kasbon;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KasbonResource extends Resource
{
    protected static ?string $model = Kasbon::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'id_petugas';

    public static function form(Schema $schema): Schema
    {
        return KasbonForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KasbonInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KasbonsTable::configure($table);
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
            'index' => ListKasbons::route('/'),
            'create' => CreateKasbon::route('/create'),
            'view' => ViewKasbon::route('/{record}'),
            'edit' => EditKasbon::route('/{record}/edit'),
        ];
    }
}
