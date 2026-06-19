<?php

namespace App\Filament\Warga\Resources\JenisIuranWargas;

use App\Filament\Warga\Resources\JenisIuranWargas\Pages\CreateJenisIuranWarga;
use App\Filament\Warga\Resources\JenisIuranWargas\Pages\EditJenisIuranWarga;
use App\Filament\Warga\Resources\JenisIuranWargas\Pages\ListJenisIuranWargas;
use App\Filament\Warga\Resources\JenisIuranWargas\Pages\ViewJenisIuranWarga;
use App\Filament\Warga\Resources\JenisIuranWargas\Schemas\JenisIuranWargaForm;
use App\Filament\Warga\Resources\JenisIuranWargas\Schemas\JenisIuranWargaInfolist;
use App\Filament\Warga\Resources\JenisIuranWargas\Tables\JenisIuranWargasTable;
use App\Filament\Warga\Resources\JenisIuranWargas\Widgets\JenisIuranWargaOverview;
use App\Models\JenisIuranWarga;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class JenisIuranWargaResource extends Resource
{
    protected static ?string $model = JenisIuranWarga::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static ?string $navigationLabel = 'Daftar Iuran';

    protected static ?string $recordTitleAttribute = 'jenis_iuran';

    protected static string|UnitEnum|null $navigationGroup = 'Iuran';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'Jenis Iuran';

    protected static ?string $pluralModelLabel = 'Jenis-jenis Iuran';

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

    /**
     * Scope records to the currently authenticated warga's RT only.
     */
    public static function getEloquentQuery(): Builder
    {
        $warga = auth()->user()?->warga;

        return parent::getEloquentQuery()->when(
            $warga,
            fn (Builder $q) => $q->where('id_rt', $warga->id_rt),
        );
    }

    public static function getWidgets(): array
    {
        return [JenisIuranWargaOverview::class];
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
