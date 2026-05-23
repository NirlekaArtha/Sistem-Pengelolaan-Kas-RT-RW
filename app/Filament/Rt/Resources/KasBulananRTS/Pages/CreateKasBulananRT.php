<?php

namespace App\Filament\Rt\Resources\KasBulananRTS\Pages;

use App\Filament\Rt\Resources\KasBulananRTS\KasBulananRTResource;
use Filament\Resources\Pages\CreateRecord;

class CreateKasBulananRT extends CreateRecord
{
    protected static string $resource = KasBulananRTResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data["id_rt"] = auth()->user()?->rt?->id;

        return $data;
    }
}
