<?php

namespace App\Filament\Rw\Resources\KasKeluarRWS\Pages;

use App\Filament\Rw\Resources\KasKeluarRWS\KasKeluarRWResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKasKeluarRW extends EditRecord
{
    protected static string $resource = KasKeluarRWResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
