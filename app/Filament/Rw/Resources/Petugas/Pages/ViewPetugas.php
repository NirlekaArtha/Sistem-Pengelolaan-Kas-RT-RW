<?php

namespace App\Filament\Rw\Resources\Petugas\Pages;

use App\Filament\Rw\Resources\Petugas\PetugasResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPetugas extends ViewRecord
{
    protected static string $resource = PetugasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
