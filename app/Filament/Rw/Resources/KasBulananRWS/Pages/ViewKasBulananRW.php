<?php

namespace App\Filament\Rw\Resources\KasBulananRWS\Pages;

use App\Filament\Rw\Resources\KasBulananRWS\KasBulananRWResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKasBulananRW extends ViewRecord
{
    protected static string $resource = KasBulananRWResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
