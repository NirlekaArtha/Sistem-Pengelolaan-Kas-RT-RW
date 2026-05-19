<?php

namespace App\Filament\Rt\Resources\KasMasukRTS\Pages;

use App\Filament\Rt\Resources\KasMasukRTS\KasMasukRTResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKasMasukRT extends EditRecord
{
    protected static string $resource = KasMasukRTResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
