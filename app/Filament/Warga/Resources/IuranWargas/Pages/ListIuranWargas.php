<?php

namespace App\Filament\Warga\Resources\IuranWargas\Pages;

use App\Enums\IuranWargaStatus;
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
            IuranWargaStatus::DIBAYAR->value => Tab::make(IuranWargaStatus::DIBAYAR->getLabel())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', IuranWargaStatus::DIBAYAR->value)),
            IuranWargaStatus::BELUM_BAYAR->value => Tab::make(IuranWargaStatus::BELUM_BAYAR->getLabel())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', IuranWargaStatus::BELUM_BAYAR->value)),
            IuranWargaStatus::TELAT->value => Tab::make(IuranWargaStatus::TELAT->getLabel())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', IuranWargaStatus::TELAT->value)),
        ];
    }
}
