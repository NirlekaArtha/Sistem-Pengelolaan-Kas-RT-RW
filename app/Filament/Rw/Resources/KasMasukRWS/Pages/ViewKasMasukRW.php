<?php

namespace App\Filament\Rw\Resources\KasMasukRWS\Pages;

use App\Filament\Rw\Resources\KasMasukRWS\KasMasukRWResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKasMasukRW extends ViewRecord
{
    protected static string $resource = KasMasukRWResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
