<?php

namespace App\Filament\Rw\Resources\RTS\Pages;

use App\Filament\Rw\Resources\RTS\RTResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRT extends ViewRecord
{
    protected static string $resource = RTResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
