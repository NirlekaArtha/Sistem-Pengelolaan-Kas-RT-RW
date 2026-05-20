<?php

namespace App\Filament\Rt\Resources\KasRTS\Pages;

use App\Filament\Rt\Resources\KasRTS\KasRTResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKasRTS extends ListRecords
{
    protected static string $resource = KasRTResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
