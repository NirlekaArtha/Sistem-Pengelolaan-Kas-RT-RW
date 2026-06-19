<?php

namespace App\Filament\Rt\Resources\IuranWargas\Pages;

use App\Enums\IuranWargaStatus;
use App\Filament\Rt\Resources\IuranWargas\IuranWargaResource;
use App\Filament\Rt\Resources\IuranWargas\Widgets\IuranWargaOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ListIuranWargas extends ListRecords
{
    protected static string $resource = IuranWargaResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }

    protected function getHeaderWidgets(): array
    {
        return [IuranWargaOverview::class];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            IuranWargaStatus::DIBAYAR->value => Tab::make(IuranWargaStatus::DIBAYAR->getLabel())->modifyQueryUsing(
                fn (Builder $query) => $query->where('status', IuranWargaStatus::DIBAYAR->value),
            ),
            IuranWargaStatus::BELUM_BAYAR->value => Tab::make(IuranWargaStatus::BELUM_BAYAR->getLabel())->modifyQueryUsing(
                fn (Builder $query) => $query->where('status', IuranWargaStatus::BELUM_BAYAR->value),
            ),
            IuranWargaStatus::TELAT->value => Tab::make(IuranWargaStatus::TELAT->getLabel())->modifyQueryUsing(
                fn (Builder $query) => $query->where('status', IuranWargaStatus::TELAT->value),
            ),
        ];
    }
}
