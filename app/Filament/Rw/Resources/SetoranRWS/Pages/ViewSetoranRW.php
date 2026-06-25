<?php

namespace App\Filament\Rw\Resources\SetoranRWS\Pages;

use App\Filament\Rw\Resources\SetoranRWS\SetoranRWResource;
use Filament\Resources\Pages\ViewRecord;

class ViewSetoranRW extends ViewRecord
{
    protected static string $resource = SetoranRWResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
