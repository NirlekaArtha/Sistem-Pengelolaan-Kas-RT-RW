<?php

namespace App\Filament\Rw\Resources\SlipGajis\Pages;

use App\Filament\Rw\Resources\SlipGajis\SlipGajiResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSlipGaji extends EditRecord
{
    protected static string $resource = SlipGajiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

