<?php

namespace App\Filament\Rt\Resources\SetoranRWS\Pages;

use App\Enums\SetoranStatusValidasi;
use App\Filament\Rt\Resources\SetoranRWS\SetoranRWResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSetoranRW extends CreateRecord
{
    protected static string $resource = SetoranRWResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['id_rt'] = auth()->user()?->rt?->id;
        $data['id_rw'] = auth()->user()?->rt?->rw?->id;
        $data['status_validasi'] = SetoranStatusValidasi::PENDING;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
