<?php

namespace App\Filament\Warga\Resources\IuranWargas\Pages;

use App\Filament\Warga\Resources\IuranWargas\IuranWargaResource;
use App\Filament\Warga\Resources\IuranWargas\Widgets\IuranWargaOverview;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListIuranWargas extends ListRecords
{
    protected static string $resource = IuranWargaResource::class;

    // Warga tidak bisa menambah iuran sendiri — header actions dikosongkan
    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            IuranWargaOverview::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),
            'dibayar' => Tab::make('Dibayar')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'dibayar')),
            'belum_bayar' => Tab::make('Belum Bayar')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'belum bayar')),
            'telat' => Tab::make('Telat')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'telat')),
        ];
    }
}
