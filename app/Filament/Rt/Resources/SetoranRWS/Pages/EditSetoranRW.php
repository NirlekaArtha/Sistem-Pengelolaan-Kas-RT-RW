<?php

namespace App\Filament\Rt\Resources\SetoranRWS\Pages;

use App\Filament\Rt\Resources\SetoranRWS\SetoranRWResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditSetoranRW extends EditRecord
{
    protected static string $resource = SetoranRWResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data["id_rt"] = auth()->user()?->rt?->id;
        $data["id_rw"] = auth()->user()?->rt?->id_rw;
        $data["status_validasi"] = "pending";

        return $data;
    }
}
