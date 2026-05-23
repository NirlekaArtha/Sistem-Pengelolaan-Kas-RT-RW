<?php

namespace App\Filament\Rt\Resources\KasRTS\Pages;

use App\Filament\Rt\Resources\KasRTS\KasRTResource;
use App\Filament\Rt\Resources\KasRTS\Widgets\KasRTOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListKasRTS extends ListRecords
{
    protected static string $resource = KasRTResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }

    protected function getHeaderWidgets(): array
    {
        return [KasRTOverview::class];
    }

    public function getTabs(): array
    {
        return [
            "all" => Tab::make("All"),
            "masuk" => Tab::make("Masuk")->modifyQueryUsing(
                fn(Builder $query) => $query->where("tipe", "masuk"),
            ),
            "keluar" => Tab::make("Keluar")->modifyQueryUsing(
                fn(Builder $query) => $query->where("tipe", "keluar"),
            ),
        ];
    }
}
