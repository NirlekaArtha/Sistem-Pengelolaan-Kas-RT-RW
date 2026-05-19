<?php

namespace App\Filament\Rw\Resources\KasBulananRWS\Pages;

use App\Filament\Rw\Resources\KasBulananRWS\KasBulananRWResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKasBulananRWS extends ListRecords
{
    protected static string $resource = KasBulananRWResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
