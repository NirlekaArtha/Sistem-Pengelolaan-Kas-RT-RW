<?php

namespace App\Filament\Rt\Resources\KasRTS\Pages;

use App\Filament\Rt\Resources\KasRTS\KasRTResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKasRT extends CreateRecord
{
    protected static string $resource = KasRTResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data["id_rt"] = auth()->user()?->rt?->id;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl("index");
    }
}
