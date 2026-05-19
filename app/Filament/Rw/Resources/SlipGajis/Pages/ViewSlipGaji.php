<?php

namespace App\Filament\Rw\Resources\SlipGajis\Pages;

use App\Filament\Rw\Resources\SlipGajis\SlipGajiResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewSlipGaji extends ViewRecord
{
    protected static string $resource = SlipGajiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
