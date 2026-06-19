<?php

namespace App\Filament\Rt\Resources\IuranWargas\Pages;

use App\Filament\Rt\Resources\IuranWargas\IuranWargaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditIuranWarga extends EditRecord
{
    protected static string $resource = IuranWargaResource::class;

    protected function getHeaderActions(): array
    {
        return [ViewAction::make(), DeleteAction::make()];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['id_rt'] = auth()->user()?->rt?->id;

        return $data;
    }
}
