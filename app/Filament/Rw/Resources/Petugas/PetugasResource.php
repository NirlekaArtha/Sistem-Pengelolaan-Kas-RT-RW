<?php

namespace App\Filament\Rw\Resources\Petugas;

use App\Filament\Rw\Resources\Petugas\Pages\CreatePetugas;
use App\Filament\Rw\Resources\Petugas\Pages\EditPetugas;
use App\Filament\Rw\Resources\Petugas\Pages\ListPetugas;
use App\Filament\Rw\Resources\Petugas\Pages\ViewPetugas;
use App\Filament\Rw\Resources\Petugas\Schemas\PetugasForm;
use App\Filament\Rw\Resources\Petugas\Schemas\PetugasInfolist;
use App\Filament\Rw\Resources\Petugas\Tables\PetugasTable;
use App\Filament\Rw\Resources\Petugas\Widgets\PetugasOverview;
use App\Models\Petugas;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class PetugasResource extends Resource
{
    protected static ?string $model = Petugas::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Data Master';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'nama';

    public static function form(Schema $schema): Schema
    {
        return PetugasForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PetugasInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PetugasTable::configure($table);
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
            'id_rw',
            auth()->user()?->rw?->id,
        );
    }

    public static function getWidgets(): array
    {
        return [PetugasOverview::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPetugas::route('/'),
            'create' => CreatePetugas::route('/create'),
            'view' => ViewPetugas::route('/{record}'),
            'edit' => EditPetugas::route('/{record}/edit'),
        ];
    }
}
