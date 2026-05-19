<?php

namespace App\Filament\Rt\Resources\Wargas\Pages;

use App\Filament\Rt\Resources\Wargas\WargaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWarga extends ViewRecord
{
    protected static string $resource = WargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
