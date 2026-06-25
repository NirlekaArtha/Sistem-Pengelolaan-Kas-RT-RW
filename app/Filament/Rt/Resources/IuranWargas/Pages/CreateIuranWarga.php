<?php

namespace App\Filament\Rt\Resources\IuranWargas\Pages;

use App\Filament\Rt\Resources\IuranWargas\IuranWargaResource;
use App\Models\IuranWarga;
use App\Services\IuranWargaBulkCreateService;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateIuranWarga extends CreateRecord
{
    protected static string $resource = IuranWargaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['id_rt'] = auth()->user()?->rt?->id;

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        return IuranWargaBulkCreateService::create($data)->first()
            ?? new IuranWarga;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Iuran warga berhasil disimpan.';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
