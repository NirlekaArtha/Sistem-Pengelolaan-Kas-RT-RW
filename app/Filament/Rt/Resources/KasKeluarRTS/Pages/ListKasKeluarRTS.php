<?php

namespace App\Filament\Rt\Resources\KasKeluarRTS\Pages;

use App\Filament\Rt\Resources\KasKeluarRTS\KasKeluarRTResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKasKeluarRTS extends ListRecords
{
    protected static string $resource = KasKeluarRTResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
