<?php

namespace App\Filament\Rw\Resources\KasKeluarRWS\Pages;

use App\Filament\Rw\Resources\KasKeluarRWS\KasKeluarRWResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKasKeluarRW extends ViewRecord
{
    protected static string $resource = KasKeluarRWResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
