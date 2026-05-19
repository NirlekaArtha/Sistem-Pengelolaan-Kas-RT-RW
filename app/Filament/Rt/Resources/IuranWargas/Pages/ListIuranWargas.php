<?php

namespace App\Filament\Rt\Resources\IuranWargas\Pages;

use App\Filament\Rt\Resources\IuranWargas\IuranWargaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListIuranWargas extends ListRecords
{
    protected static string $resource = IuranWargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
