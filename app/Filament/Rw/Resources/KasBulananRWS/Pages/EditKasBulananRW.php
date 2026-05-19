<?php

namespace App\Filament\Rw\Resources\KasBulananRWS\Pages;

use App\Filament\Rw\Resources\KasBulananRWS\KasBulananRWResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKasBulananRW extends EditRecord
{
    protected static string $resource = KasBulananRWResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
