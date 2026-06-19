<?php

namespace App\Filament\Rt\Resources\IuranWargas\Pages;

use App\Filament\Rt\Resources\IuranWargas\IuranWargaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateIuranWarga extends CreateRecord
{
    protected static string $resource = IuranWargaResource::class;

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
