<?php

namespace App\Filament\Rt\Resources\KasMasukRTS;

use App\Filament\Rt\Resources\KasMasukRTS\Pages\CreateKasMasukRT;
use App\Filament\Rt\Resources\KasMasukRTS\Pages\EditKasMasukRT;
use App\Filament\Rt\Resources\KasMasukRTS\Pages\ListKasMasukRTS;
use App\Filament\Rt\Resources\KasMasukRTS\Pages\ViewKasMasukRT;
use App\Filament\Rt\Resources\KasMasukRTS\Schemas\KasMasukRTForm;
use App\Filament\Rt\Resources\KasMasukRTS\Schemas\KasMasukRTInfolist;
use App\Filament\Rt\Resources\KasMasukRTS\Tables\KasMasukRTSTable;
use App\Models\KasMasukRT;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KasMasukRTResource extends Resource
{
    protected static ?string $model = KasMasukRT::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'sumber';

    public static function form(Schema $schema): Schema
    {
        return KasMasukRTForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return KasMasukRTInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KasMasukRTSTable::configure($table);
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
            'index' => ListKasMasukRTS::route('/'),
            'create' => CreateKasMasukRT::route('/create'),
            'view' => ViewKasMasukRT::route('/{record}'),
            'edit' => EditKasMasukRT::route('/{record}/edit'),
        ];
    }
}
