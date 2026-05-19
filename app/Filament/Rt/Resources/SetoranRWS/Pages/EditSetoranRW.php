<?php

namespace App\Filament\Rt\Resources\SetoranRWS\Pages;

use App\Filament\Rt\Resources\SetoranRWS\SetoranRWResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSetoranRW extends EditRecord
{
    protected static string $resource = SetoranRWResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
