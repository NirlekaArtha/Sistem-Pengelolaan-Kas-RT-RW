<?php

namespace App\Filament\Rw\Resources\KasRWS\Pages;

use App\Filament\Rw\Resources\KasRWS\KasRWResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKasRW extends EditRecord
{
    protected static string $resource = KasRWResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
