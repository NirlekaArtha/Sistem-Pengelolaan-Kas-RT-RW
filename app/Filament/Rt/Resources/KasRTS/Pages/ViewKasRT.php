<?php

namespace App\Filament\Rt\Resources\KasRTS\Pages;

use App\Filament\Rt\Resources\KasRTS\KasRTResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKasRT extends ViewRecord
{
    protected static string $resource = KasRTResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
