<?php

namespace App\Filament\Rt\Resources\JenisIuranWargas\Pages;

use App\Filament\Rt\Resources\JenisIuranWargas\JenisIuranWargaResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewJenisIuranWarga extends ViewRecord
{
    protected static string $resource = JenisIuranWargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
