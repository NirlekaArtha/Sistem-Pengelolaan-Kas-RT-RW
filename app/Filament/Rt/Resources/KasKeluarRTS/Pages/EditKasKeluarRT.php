<?php

namespace App\Filament\Rt\Resources\KasKeluarRTS\Pages;

use App\Filament\Rt\Resources\KasKeluarRTS\KasKeluarRTResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKasKeluarRT extends EditRecord
{
    protected static string $resource = KasKeluarRTResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
