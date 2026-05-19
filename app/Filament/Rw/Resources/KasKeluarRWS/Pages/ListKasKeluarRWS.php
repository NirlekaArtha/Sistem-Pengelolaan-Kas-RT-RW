<?php

namespace App\Filament\Rw\Resources\KasKeluarRWS\Pages;

use App\Filament\Rw\Resources\KasKeluarRWS\KasKeluarRWResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKasKeluarRWS extends ListRecords
{
    protected static string $resource = KasKeluarRWResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
