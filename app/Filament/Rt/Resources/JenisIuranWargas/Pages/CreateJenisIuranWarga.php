<?php

namespace App\Filament\Rt\Resources\JenisIuranWargas\Pages;

use App\Filament\Rt\Resources\JenisIuranWargas\JenisIuranWargaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateJenisIuranWarga extends CreateRecord
{
    protected static string $resource = JenisIuranWargaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['id_rt'] = auth()->user()?->rt?->id;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
