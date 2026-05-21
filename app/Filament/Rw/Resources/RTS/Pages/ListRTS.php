<?php

namespace App\Filament\Rw\Resources\RTS\Pages;

use App\Filament\Rw\Resources\RTS\RTResource;
use App\Filament\Rw\Resources\RTS\Widgets\RTOverview;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRTS extends ListRecords
{
    protected static string $resource = RTResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            RTOverview::class,
        ];
    }
}
