<?php

namespace App\Filament\Rt\Resources\KasRTS\Pages;

use App\Enums\KasTipe;
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
            'all' => Tab::make('All'),
            KasTipe::MASUK->value => Tab::make(KasTipe::MASUK->getLabel())->modifyQueryUsing(
                fn (Builder $query) => $query->where('tipe', KasTipe::MASUK->value),
            ),
            KasTipe::KELUAR->value => Tab::make(KasTipe::KELUAR->getLabel())->modifyQueryUsing(
                fn (Builder $query) => $query->where('tipe', KasTipe::KELUAR->value),
            ),
        ];
    }
}
