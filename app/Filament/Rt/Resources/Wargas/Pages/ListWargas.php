<?php

namespace App\Filament\Rt\Resources\Wargas\Pages;

use App\Filament\Rt\Resources\Wargas\WargaResource;
use App\Filament\Rt\Resources\Wargas\Widgets\WargaOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWargas extends ListRecords
{
    protected static string $resource = WargaResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }

    protected function getHeaderWidgets(): array
    {
        return [WargaOverview::class];
    }
}
