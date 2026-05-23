<?php

namespace App\Filament\Rt\Resources\KasBulananRTS\Pages;

use App\Filament\Rt\Resources\KasBulananRTS\KasBulananRTResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditKasBulananRT extends EditRecord
{
    protected static string $resource = KasBulananRTResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

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
