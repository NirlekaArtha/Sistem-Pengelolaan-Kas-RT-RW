<?php

namespace App\Filament\Rw\Resources\KasMasukRWS\Pages;

use App\Filament\Rw\Resources\KasMasukRWS\KasMasukRWResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKasMasukRW extends EditRecord
{
    protected static string $resource = KasMasukRWResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
