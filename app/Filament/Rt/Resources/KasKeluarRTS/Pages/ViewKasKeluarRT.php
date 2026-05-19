<?php

namespace App\Filament\Rt\Resources\KasKeluarRTS\Pages;

use App\Filament\Rt\Resources\KasKeluarRTS\KasKeluarRTResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKasKeluarRT extends ViewRecord
{
    protected static string $resource = KasKeluarRTResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
