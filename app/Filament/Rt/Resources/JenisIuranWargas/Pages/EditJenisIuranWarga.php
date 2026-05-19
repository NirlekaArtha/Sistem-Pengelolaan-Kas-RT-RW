<?php

namespace App\Filament\Rt\Resources\JenisIuranWargas\Pages;

use App\Filament\Rt\Resources\JenisIuranWargas\JenisIuranWargaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditJenisIuranWarga extends EditRecord
{
    protected static string $resource = JenisIuranWargaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
