<?php

namespace App\Filament\Rt\Resources\KasBulananRTS\Pages;

use App\Filament\Rt\Resources\KasBulananRTS\KasBulananRTResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKasBulananRTS extends ListRecords
{
    protected static string $resource = KasBulananRTResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
