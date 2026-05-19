<?php

namespace App\Filament\Rt\Resources\SetoranRWS\Pages;

use App\Filament\Rt\Resources\SetoranRWS\SetoranRWResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSetoranRW extends ViewRecord
{
    protected static string $resource = SetoranRWResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
