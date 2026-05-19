<?php

namespace App\Filament\Rw\Resources\Kasbons\Pages;

use App\Filament\Rw\Resources\Kasbons\KasbonResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKasbon extends EditRecord
{
    protected static string $resource = KasbonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
