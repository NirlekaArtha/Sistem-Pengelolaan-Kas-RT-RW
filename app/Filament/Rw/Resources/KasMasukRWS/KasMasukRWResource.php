<?php

namespace App\Filament\Rw\Resources\KasMasukRWS;

use App\Filament\Rw\Resources\KasMasukRWS\Pages\CreateKasMasukRW;
use App\Filament\Rw\Resources\KasMasukRWS\Pages\EditKasMasukRW;
use App\Filament\Rw\Resources\KasMasukRWS\Pages\ListKasMasukRWS;
use App\Filament\Rw\Resources\KasMasukRWS\Pages\ViewKasMasukRW;
use App\Filament\Rw\Resources\KasMasukRWS\Schemas\KasMasukRWForm;
use App\Filament\Rw\Resources\KasMasukRWS\Schemas\KasMasukRWInfolist;
use App\Filament\Rw\Resources\KasMasukRWS\Tables\KasMasukRWSTable;
use App\Models\KasMasukRW;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KasMasukRWResource extends Resource
{
    protected static ?string $model = KasMasukRW::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'sumber';

    public static function form(Schema $schema): Schema
    {
        return KasMasukRWForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KasMasukRWInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KasMasukRWSTable::configure($table);
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
            'index' => ListKasMasukRWS::route('/'),
            'create' => CreateKasMasukRW::route('/create'),
            'view' => ViewKasMasukRW::route('/{record}'),
            'edit' => EditKasMasukRW::route('/{record}/edit'),
        ];
    }
}
