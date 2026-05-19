<?php

namespace App\Filament\Rt\Resources\KasMasukRTS\Pages;

use App\Filament\Rt\Resources\KasMasukRTS\KasMasukRTResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewKasMasukRT extends ViewRecord
{
    protected static string $resource = KasMasukRTResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
