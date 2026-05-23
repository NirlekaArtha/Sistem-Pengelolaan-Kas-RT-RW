<?php

namespace App\Filament\Rt\Resources\IuranWargas\Pages;

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
            "all" => Tab::make("All"),
            "dibayar" => Tab::make("Dibayar")->modifyQueryUsing(
                fn(Builder $query) => $query->where("status", "dibayar"),
            ),
            "belum bayar" => Tab::make("Belum Bayar")->modifyQueryUsing(
                fn(Builder $query) => $query->where("status", "belum bayar"),
            ),
            "telat" => Tab::make("Telat")->modifyQueryUsing(
                fn(Builder $query) => $query->where("status", "telat"),
            ),
        ];
    }
}
