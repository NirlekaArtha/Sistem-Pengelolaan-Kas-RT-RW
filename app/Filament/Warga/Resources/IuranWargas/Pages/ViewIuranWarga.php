<?php

namespace App\Filament\Warga\Resources\IuranWargas\Pages;

use App\Filament\Warga\Resources\IuranWargas\IuranWargaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewIuranWarga extends ViewRecord
{
    protected static string $resource = IuranWargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
