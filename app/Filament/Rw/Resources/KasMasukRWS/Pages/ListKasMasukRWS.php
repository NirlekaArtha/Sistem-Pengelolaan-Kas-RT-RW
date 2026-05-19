<?php

namespace App\Filament\Rw\Resources\KasMasukRWS\Pages;

use App\Filament\Rw\Resources\KasMasukRWS\KasMasukRWResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKasMasukRWS extends ListRecords
{
    protected static string $resource = KasMasukRWResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
