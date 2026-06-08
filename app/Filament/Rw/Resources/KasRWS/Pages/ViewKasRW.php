<?php

namespace App\Filament\Rw\Resources\KasRWS\Pages;

use App\Filament\Rw\Resources\KasRWS\KasRWResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKasRW extends ViewRecord
{
    protected static string $resource = KasRWResource::class;

    protected function getHeaderActions(): array
    {
        return [EditAction::make(), DeleteAction::make()];
    }
}
