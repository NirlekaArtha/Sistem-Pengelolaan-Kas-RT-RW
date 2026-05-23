<?php

namespace App\Filament\Rt\Resources\KasRTS\Pages;

use App\Filament\Rt\Resources\KasRTS\KasRTResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKasRT extends EditRecord
{
    protected static string $resource = KasRTResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data["id_rt"] = auth()->user()?->rt?->id;
        return $data;
    }
}
