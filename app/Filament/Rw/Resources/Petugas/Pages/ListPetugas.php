<?php

namespace App\Filament\Rw\Resources\Petugas\Pages;

use App\Filament\Rw\Resources\Petugas\PetugasResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPetugas extends ListRecords
{
    protected static string $resource = PetugasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
