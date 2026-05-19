<?php

namespace App\Filament\Rw\Resources\Kasbons\Pages;

use App\Filament\Rw\Resources\Kasbons\KasbonResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKasbon extends ViewRecord
{
    protected static string $resource = KasbonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
