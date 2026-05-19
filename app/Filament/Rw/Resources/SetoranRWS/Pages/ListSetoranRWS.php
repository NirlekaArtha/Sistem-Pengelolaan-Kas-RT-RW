<?php

namespace App\Filament\Rw\Resources\SetoranRWS\Pages;

use App\Filament\Rw\Resources\SetoranRWS\SetoranRWResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSetoranRWS extends ListRecords
{
    protected static string $resource = SetoranRWResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
