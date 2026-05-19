<?php

namespace App\Filament\Rt\Resources\KasBulananRTS\Pages;

use App\Filament\Rt\Resources\KasBulananRTS\KasBulananRTResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKasBulananRT extends ViewRecord
{
    protected static string $resource = KasBulananRTResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
