<?php

namespace App\Filament\Rt\Resources\JenisIuranWargas;

use App\Filament\Rt\Resources\JenisIuranWargas\Pages\CreateJenisIuranWarga;
use App\Filament\Rt\Resources\JenisIuranWargas\Pages\EditJenisIuranWarga;
use App\Filament\Rt\Resources\JenisIuranWargas\Pages\ListJenisIuranWargas;
use App\Filament\Rt\Resources\JenisIuranWargas\Pages\ViewJenisIuranWarga;
use App\Filament\Rt\Resources\JenisIuranWargas\Schemas\JenisIuranWargaForm;
use App\Filament\Rt\Resources\JenisIuranWargas\Schemas\JenisIuranWargaInfolist;
use App\Filament\Rt\Resources\JenisIuranWargas\Tables\JenisIuranWargasTable;
use App\Models\JenisIuranWarga;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JenisIuranWargaResource extends Resource
{
    protected static ?string $model = JenisIuranWarga::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'jenis_iuran';

    public static function form(Schema $schema): Schema
    {
        return JenisIuranWargaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return JenisIuranWargaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JenisIuranWargasTable::configure($table);
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
            'index' => ListJenisIuranWargas::route('/'),
            'create' => CreateJenisIuranWarga::route('/create'),
            'view' => ViewJenisIuranWarga::route('/{record}'),
            'edit' => EditJenisIuranWarga::route('/{record}/edit'),
        ];
    }
}
