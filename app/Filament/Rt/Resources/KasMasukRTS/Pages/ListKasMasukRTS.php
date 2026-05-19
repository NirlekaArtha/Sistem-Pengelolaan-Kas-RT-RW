<?php

namespace App\Filament\Rt\Resources\KasMasukRTS\Pages;

use App\Filament\Rt\Resources\KasMasukRTS\KasMasukRTResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKasMasukRTS extends ListRecords
{
    protected static string $resource = KasMasukRTResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
